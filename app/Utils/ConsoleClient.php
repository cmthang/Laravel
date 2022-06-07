<?php
namespace App\Utils;

class ConsoleClient
{
    protected $api_url = 'http://console.3scloud.private/api/';

    protected $farm_api_url = 'http://farmapi.3scloud.private:8000/';

    protected $farm_api_url_2 = 'http://deadline-assetstore.3scloud.private:8002/';

    public function __construct()
    {
    }

    public function execute($api_point, Array $data, $method = 'GET', $farm_api = 0)
    {
        if($farm_api == 1){
            $url = $this->farm_api_url . $api_point;
        }elseif($farm_api == 2){
            $url = $this->farm_api_url_2 . $api_point;
        }else{
            // where we make the API petition
            $url = $this->api_url . $api_point;
        }
        
        // headers to authenticate
        $header = [
            'Content-Type: application/json',
        ];

        // data we send to the API
        $data += [];
        $data = json_encode($data);

        //open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $curlResult = curl_exec($ch);

        $result = [];

        if (!curl_errno($ch)) {
            switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 200:  # OK
                    $result = json_decode($curlResult, TRUE);

                    break;
                default:
                    break;
            }
        }

        curl_close($ch);

        return $result;
    }

    public function addCredits($data)
    {
        $result = $this->execute('admin/add-credit', $data, 'POST');

        return $result;
    }

    public function browseOutput($data)
    {
        $result = $this->execute('admin/jobs/browse-output', $data);

        return $result;
    }

    public function getUrlOutput($data)
    {
        $result = $this->execute('admin/jobs/get-url-output', $data);

        return $result;
    }

    public function getLogTask($data)
    {
        $result = $this->execute('internal/jobs/get-log-tasks', $data);

        return $result;
    }

    public function sceneAnalyze($data)
    {
        $result = $this->execute('admin/scene-analyze/get-analysis-detail', $data);

        return $result;
    }

    public function editPromotion($data, $editFlag = TRUE)
    {
        $url = 'admin/promotion-code/create';
        if ($editFlag) {
            $url = 'admin/promotion-code/edit';
        }

        $result = $this->execute($url, $data, 'POST');

        return $result;
    }

    public function editAffLink($data, $editFlag = TRUE)
    {
        $url = 'admin/create-link-affiliate';
        if ($editFlag) {
            $url = 'admin/edit-link-affiliate';
        }

        $result = $this->execute($url, $data, 'POST');

        return $result;
    }

    public function removeAffiliateLink($data)
    {
        $result = $this->execute('admin/delete-link-affiliate', $data, 'POST');

        return $result;
    }

    public function editPromotionGift($data, $editFlag = TRUE)
    {
        $url = 'admin/gift/add-gift';
        if ($editFlag) {
            $url = 'admin/gift/edit-gift';
        }

        $result = $this->execute($url, $data, 'POST');

        return $result;
    }

    public function removePromotion($data)
    {
        $result = $this->execute('admin/promotion-code/delete', $data, 'POST');

        return $result;
    }

    public function removePromotionGift($data)
    {
        $result = $this->execute('admin/gift/delete-gift', $data, 'POST');

        return $result;
    }

    public function editUserLevel($data)
    {
        $result = $this->execute('admin/user/update-level', $data, 'POST');

        return $result;
    }

    public function editUserRoles($data)
    {
        $result = $this->execute('admin/user/update-admin', $data, 'POST');

        return $result;
    }

    public function updateJobAmount($data)
    {
        $result = $this->execute('admin/jobs/update-amount-machine', $data, 'POST');

        return $result;
    }

    public function activeUser($userId)
    {
        $result = $this->execute('admin/active-user', ['user_id' => $userId], 'POST');

        return $result;
    }

    public function deActiveUser($userId)
    {
        $result = $this->execute('admin/deactive-user', ['user_id' => $userId], 'POST');

        return $result;
    }

    public function updateJobStatus($data)
    {
        if($data['status'] == 'restart'){
            $data['status'] = 'paused';
            $callResult = $this->execute('admin/jobs/update-status', $data, 'POST');
            
            $data['status'] = 'resumed';
            $callResult = $this->execute('admin/jobs/update-status', $data, 'POST');
        }else {
            $callResult = $this->execute('admin/jobs/update-status', $data, 'POST');
        }

        $needResult = [
            'success' => FALSE,
            'message' => 'Fail.',
        ];

        if (isset($callResult['data'])) {
            $resultData = json_decode($callResult['data'], TRUE);

            $needResult['message'] = $resultData['mes'];
            if ($resultData['code'] == 'succeed') {
                $needResult['success'] = TRUE;
            }
        }

        return $needResult;
    }

    public function updateUserFeedback($data)
    {
        $callResult = $this->execute('admin/user/update-feedback', $data, 'POST');

        return $callResult;
    }

    public function markedHacker($data)
    {
        $callResult = $this->execute('admin/user/marked-as-hacker', $data, 'POST');
        return $callResult;
    }
    
    public function getImageServersZone($data)
    {
        $callResult = $this->execute('farm/image-server', $data, 'GET', 1);

        return $callResult;
    }

    public function updateImageServersZone($data)
    {
        $callResult = $this->execute('farm/image-server', $data, 'POST', 1);

        return $callResult;
    }

    public function deleteImageServersZone($data)
    {
        $callResult = $this->execute('farm/image-server', $data, 'DELETE', 1);

        return $callResult;
    }

    public function copyImageServerToZone($data)
    {
        $callResult = $this->execute('imageserver/copy', $data, 'POST', 2);
        $callResult = $this->execute('admin/notify-status-copy-image', ['zone' => $data['zone'],'from_folder' => $data['from_folder'],'status' => 'copying'], 'POST');
        return $callResult;
    }

    public function requestGetMoreInfo($data)
    {
        $callResult = $this->execute('admin/user/request-more-info', $data, 'POST');
        return $callResult;
    }

    public function updateUserMultiAzForUser($data)
    {
        $result = $this->execute('admin/update-user-multiaz', $data, 'POST');

        return $result;
    }

    public function updateJobCostF($data)
    {
        $result = $this->execute('admin/jobs/update-job-cost', $data, 'POST');

        return $result;
    }

    public function updateUserLimitPreview($data)
    {
        $result = $this->execute('admin/user/update-preview-limit', $data, 'POST');

        return $result;
    }

    public function updateUserAutoSyncAsset($data)
    {
        $result = $this->execute('admin/user/update-auto-sync-asset', $data, 'POST');

        return $result;
    }

    public function deleteDomain($data)
    {
        $result = $this->execute('admin/delete-domain', $data, 'POST');

        return $result;
    }

    public function addDomain($data)
    {
        $result = $this->execute('admin/add-domain', $data, 'POST');

        return $result;
    }

    public function addGroupDiscount($data)
    {
        $result = $this->execute('admin/add-group-discount', $data, 'POST');

        return $result;
    }

    public function editGroupDiscount($data)
    {
        $result = $this->execute('admin/edit-group-discount', $data, 'POST');

        return $result;
    }

    public function deleteGroupDiscount($data)
    {
        $result = $this->execute('admin/delete-group-discount', $data, 'POST');

        return $result;
    }

    public function updateUserCompanyName($data)
    {
        $result = $this->execute('admin/update-user-company', $data, 'POST');

        return $result;
    }

    public function updateUserNoteCL($data)
    {
        $result = $this->execute('admin/update-user-note', $data, 'POST');

        return $result;
    }

    public function updateUserCountryCode($data)
    {
        $result = $this->execute('admin/update-user-country', $data, 'POST');

        return $result;
    }

    public function updateUserStudentField($data)
    {
        $result = $this->execute('admin/user/update-user-student-filed', $data, 'POST');

        return $result;
    }

    public function updateUserDownloadDatasetField($data)
    {
        $result = $this->execute('admin/user/update-user-download-dataset-field', $data, 'POST');

        return $result;
    }

    public function overrideUserLv($data)
    {
        $result = $this->execute('admin/user/update-user-ovr-lv-field', $data, 'POST');

        return $result;
    }

    public function updateJobRenderTimes($data)
    {
        $result = $this->execute('admin/jobs/update-render-time', $data, 'POST');

        return $result;
    }

    public function updateJobPackageType($data)
    {
        $result = $this->execute('admin/jobs/update-machine-type', $data, 'POST');

        return $result;
    }

    public function updateDSWPT($data)
    {
        $result = $this->execute('admin/jobs/update-default-machine-type', $data, 'POST');

        return $result;
    }

    public function markAsOldUser($data)
    {
        $result = $this->execute('admin/user/mark-as-old-user', $data, 'POST');

        return $result;
    }

    public function updateUserRegion($data)
    {
        $result = $this->execute('admin/user/update-region', $data, 'POST');

        return $result;
    }

    public function updateSttRegion($data)
    {
        $result = $this->execute('admin/update-region', $data, 'POST');

        return $result;
    }

    public function updateUserSttColumn($data)
    {
        $result = $this->execute('admin/user/update-status-column', $data, 'POST');

        return $result;
    }

    public function updateUserConfig($data)
    {
        $result = $this->execute('admin/user/update-user-config', $data, 'POST');

        return $result;
    }

    public function notifyReloadUserApp($data)
    {
        $result = $this->execute('admin/user/notify-update-app', $data, 'POST');

        return $result;
    }

    public function updateCSENV($data, $editFlag = TRUE)
    {
        $url = 'admin/add-custom-system-env';
        if ($editFlag) {
            $url = 'admin/edit-custom-system-env';
        }

        $result = $this->execute($url, $data, 'POST');

        return $result;
    }

    public function updateRenderEngine($data, $editFlag = TRUE)
    {
        $url = 'admin/engine-version/add';
        if ($editFlag) {
            $url = 'admin/engine-version/update';
        }

        $result = $this->execute($url, $data, 'POST');

        return $result;
    }

    public function forceSync($data)
    {
        $result = $this->execute('admin/force-sync', $data, 'POST');

        return $result;
    }

    public function forceSyncOutput($data)
    {
        $result = $this->execute('admin/force-sync-output', $data, 'POST');

        return $result;
    }

    public function deleteEngineVersion($data){
        $result = $this->execute('admin//engine-version/delete', $data, 'POST');

        return $result;
    }

    public function updateRenderSupport($data, $editFlag = TRUE)
    {
        
        $url = 'admin/support-software/add';

        if ($editFlag) {
           $url = 'admin/support-software/update';

        }

//        dd($url, $data );
          $result = $this->execute($url, $data, 'POST');        

          $result = $this -> execute($url, $data);

        return $result;
    }


    public function deleteSupportSoftware($data){
        dd($data);
        $result = $this->execute('admin//support-software/delete', $data, 'POST');
        return $result;
    }


}