<?php
namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\PromotionCode;
use App\Models\LinkAffiliate;
use App\Models\RenderJob;
use App\Models\RenderTaskDetail;
use App\Utils\Common;
use App\Utils\ConsoleClient;
use App\Utils\Constant;
use Auth,Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromotionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin.auth');

        $user = Auth::guard('admin')->user();
        if ($user && $user->roles != Constant::USER_ROLE_SUPER_ADMIN) {
            return redirect()->route('index')->send();
        }
    }

    public function index(Request $request)
    {
        return view('promotion.index', compact($request->user()));
    }

    public function edit(Request $request, $id = NULL)
    {
        if ($id) {
            $promotionCoupon = PromotionCode::find($id);
        } else {
            $promotionCoupon = new PromotionCode();
        }

        if ($request->isMethod('POST')) {
            $validator = [];
            $validator['code'] = Rule::unique('promotion_code', 'code')->ignore($id, 'id');
            if($request->get('is_giftCode') == 0){
                $validator['promotion_value'] = 'required';
            }
            $validator['coupon_type'] = 'required';
            $validator['value_type'] = 'required';
            $validator['number_of_uses'] = 'required|numeric';
            $validator['number_of_uses_per_user'] = 'required|numeric';

            $this->validate($request, $validator);

            $data = [];
            $data['code'] = $request->get('code');
            $data['status'] = $request->get('status', 0);
            $data['promotion_value'] = $request->get('promotion_value');
            $data['coupon_type'] = $request->get('coupon_type');
            $data['valid_date_from'] = $request->get('valid_date_from');
            $data['valid_date_to'] = $request->get('valid_date_to');
            $data['value_type'] = $request->get('value_type');

            $data['code_type'] = ($request->get('is_giftCode') == 1) ? 'gift_code' : 'promotion_code';
            $data['gift_value'] = ($request->get('is_giftCode') == 1) ? $request->get('gift_value') : 0;

            $dependValueFlag = $request->get('depend_payment_value', 0);
            if ($dependValueFlag) {
                $data['depend_payment_value'] = Common::buildDepentPaymentText($request->get('depend'));
            } else {
                $data['depend_payment_value'] = $dependValueFlag;
            }

            $data['number_of_uses'] = $request->get('number_of_uses');
            $data['number_of_uses_per_user'] = $request->get('number_of_uses_per_user');
            $data['note'] = $request->get('note', '');

            $editFlag = FALSE;
            if ($promotionCoupon->id) {
                $editFlag = TRUE;
            }

            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->editPromotion($data, $editFlag);

            if ($resultConsole ==  200) {
                return Redirect::route('promotion.coupon.index')->with('success', 'Update success');
            }
        }

        return view('promotion.coupon_edit', compact('promotionCoupon'));
    }

    public function delete(Request $request, $id)
    {
        $promotionCoupon = PromotionCode::find($id);
        $result = [];
        $result['success'] = FALSE;
        $result['message'] = 'Remove Fail';

        if ($request->ajax()) {
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->removePromotion(['code' => $promotionCoupon->code]);

            if ($resultConsole == 200) {
                $result['success'] = TRUE;
                $result['message'] = 'Remove Success';
            }
        }

        return \Response::json($result);
    }

    public function gift(Request $request)
    {
        return view('promotion.index_gift', compact(''));
    }

    public function giftEdit(Request $request, $id = NULL)
    {
        $giftConditionSetting = [
            'type' => 'all',
            'value' => 'true',
        ];

        if ($id) {
            $promotionGift = Gift::find($id);
            $settingTmp = json_decode($promotionGift->conditions, TRUE);
            if ($settingTmp) {
                $key = array_key_first($settingTmp);
                $giftConditionSetting['type'] = $key;
                $giftConditionSetting['value'] = $settingTmp[$key];
            }
        } else {
            $promotionGift = new Gift();
        }

        if ($request->isMethod('POST')) {
            $validator = [];
            $validator['promotion_code'] = Rule::unique('gift', 'promotion_code')->ignore($id, 'id');
            $validator['title'] = 'required';
            $validator['gift_code'] = 'required';

            $this->validate($request, $validator);

            $data = [];
            $data['promotion_code'] = $request->get('promotion_code');
            $data['title'] = $request->get('title');
            $data['subtitle'] = $request->get('subtitle');
            $data['description'] = $request->get('description');
            $data['active'] = $request->get('active', 0);
            $data['promotion_code'] = $request->get('promotion_code');
            $data['gift_code'] = $request->get('gift_code');
            $data['value'] = $request->get('value');
            $data['type'] = $request->get('type');
            $data['valid_date_from'] = $request->get('valid_date_from');
            $data['valid_date_to'] = $request->get('valid_date_to');
            $data['conditions'] = $request->get('conditions', []);

            $editFlag = FALSE;
            if ($promotionGift->id) {
                $data['gift_id'] = $promotionGift->id;
                $editFlag = TRUE;
            }

            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->editPromotionGift($data, $editFlag);

            if ($resultConsole ==  200) {
                return Redirect::route('promotion.gift.index')->with('success', 'Update success');
            }
        }

        return view('promotion.edit_gift', compact('promotionGift', 'giftConditionSetting'));
    }

    public function giftDelete(Request $request, $id)
    {
        $promotionGift = Gift::find($id);
        $result = [];
        $result['success'] = FALSE;
        $result['message'] = 'Remove Fail.';

        if ($request->ajax()) {
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->removePromotionGift(['gift_id' => $promotionGift->id]);

            if ($resultConsole == 200) {
                $result['success'] = TRUE;
                $result['message'] = 'Remove Success';
            }
        }

        return \Response::json($result);
    }

    public function giftBuildCondition(Request $request)
    {
        $type = $request->get('type');
        $value = $request->get('value');
        $giftConditionSetting = json_decode($value, TRUE);

        return view('promotion.edit_gift_condition', compact('type', 'giftConditionSetting'));
    }

    public function linkAffiliate(Request $request)
    {
        return view('promotion.index_aff_link', compact($request->user));
    }

    public function editAffiliateLink(Request $request, $id = NULL)
    {
        if ($id != NULL) {
            $linkAffiliate = LinkAffiliate::find($id);
        } else {
            $linkAffiliate = new LinkAffiliate();
        }

        if ($request->isMethod('POST')) {
            $validator = [];
            $validator['code'] = 'required';
            $validator['user_root_value'] = 'required|numeric';
            $validator['user_use_aff_value'] = 'required|numeric';
            $validator['number_of_uses'] = 'required|numeric';
            $validator['number_of_uses_per_user'] = 'required|numeric';

            $this->validate($request, $validator);

            $data = [];
            $data['code'] = $request->get('code');
            $data['status'] = $request->get('status', 0);
            $data['user_root_value'] = $request->get('user_root_value');
            $data['user_use_aff_value'] = $request->get('user_use_aff_value');
            $data['number_of_uses'] = $request->get('number_of_uses');
            $data['number_of_uses_per_user'] = $request->get('number_of_uses_per_user');
            $data['note'] = $request->get('note', '');

            $editFlag = FALSE;
            if ($linkAffiliate->id) {
                $data['id'] = $linkAffiliate->id;
                $editFlag = TRUE;
            }

            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->editAffLink($data, $editFlag);

            if ($resultConsole) {
                return Redirect::route('promotion.link_affiliate.index')->with('success', 'Update success');
            }
        }

        return view('promotion.edit_affiliate_link', compact('linkAffiliate'));
    }

    public function deleteAffiliateLink(Request $request, $id)
    {
        $linkAffiliate = LinkAffiliate::find($id);
        $result = [];
        $result['success'] = FALSE;
        $result['message'] = 'Remove Fail.';

        if ($request->ajax()) {
            $consoleClient = new ConsoleClient();
            $resultConsole = $consoleClient->removeAffiliateLink(['id' => $linkAffiliate->id]);

            if ($resultConsole == 200) {
                $result['success'] = TRUE;
                $result['message'] = 'Remove Success';
            }
        }

        return \Response::json($result);
    }
}