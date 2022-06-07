<?php

namespace App\Utils;

use App\Models\Field;
use App\Models\Order;
use App\Models\Price;
use App\Models\RenderJob;
use App\Models\Subscribe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManager;
use Pusher\Pusher;
use Pusher\PusherException;
use Request;
use Excel;

class Common
{
    public static function genRandomString($length = 8, $addTimeStamp = TRUE)
    {
        $a = str_split("abcdefghijklmnopqrstuvwxyABCDEFGHIJKLMNOPQRSTUVWXY0123456789");
        shuffle($a);

        $randomString = substr(implode($a), 0, $length);

        if ($addTimeStamp) {
            $randomString .= date('His');
        }

        return $randomString;
    }

    public static function createDir($dir)
    {
        if (!file_exists(str_replace('//', '/', $dir))) {
            mkdir(str_replace('//', '/', $dir), 0777, TRUE);
        }
    }

    public static function removeFile($pathFile)
    {
        if (empty($pathFile)) {
            return FALSE;
        }

        return is_file($pathFile) ? @unlink($pathFile) : @rmdir($pathFile);
    }

    public static function checkImageBase64($string)
    {
        $img = @imagecreatefromstring(base64_decode($string));

        if (!$img) {
            return FALSE;
        }

        return TRUE;
    }

    static public function genPath($id)
    {
        $retVal = '';
        $retVal .= $id >> 30;
        $retVal .= '/';
        $retVal .= $id >> 20;
        $retVal .= '/';
        $retVal .= $id >> 10;
        $retVal .= '/';
        $retVal .= $id % 1024;

        return $retVal;
    }

    public static function copyFile($oldFile, $newFile, $oldDelete = FALSE)
    {
        if (file_exists($oldFile)) {
            if ($oldDelete) {
                rename($oldFile, $newFile);
            } else {
                copy($oldFile, $newFile);
            }
        }
    }

    public static function scanDir($pathDir)
    {
        $fileInDir = [];

        if (file_exists($pathDir)) {
            $files = scandir($pathDir);
            foreach ($files as $file) {
                if (in_array($file, ['.', '..'])) {
                    continue;
                }

                $fileInDir[] = $file;
            }
        }

        return $fileInDir;
    }

    public static function convertNonVietnamese($text)
    {
        //Charachters must be in ASCII and certain ones aint allowed
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace("/(ä|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $text);
        $text = str_replace("ç", "c", $text);
        $text = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $text);
        $text = preg_replace("/(ì|í|î|ị|ỉ|ĩ)/", 'i', $text);
        $text = preg_replace("/(ö|ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $text);
        $text = preg_replace("/(ü|ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $text);
        $text = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $text);
        $text = preg_replace("/(đ)/", 'd', $text);

        //CHU HOA
        $text = preg_replace("/(Ä|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $text);
        $text = str_replace("Ç", "C", $text);
        $text = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $text);
        $text = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $text);
        $text = preg_replace("/(Ö|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $text);
        $text = preg_replace("/(Ü|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $text);
        $text = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $text);
        $text = preg_replace("/(Đ)/", 'D', $text);
        $text = preg_replace("/[^a-zA-Z0-9\-\_]/", ' ', $text);
        $text = str_replace("     ", ' ', $text);
        $text = str_replace("    ", ' ', $text);
        $text = str_replace("   ", ' ', $text);
        $text = str_replace("  ", ' ', $text);
        $text = str_replace(" - ", ' ', trim($text));
        $text = str_replace(" -", ' ', trim($text));
        $text = str_replace("- ", ' ', trim($text));
        $text = str_replace(" ", '-', trim($text));

        return strtolower($text);
    }

    public static function genPathAlias($text)
    {
        $alias = self::convertNonVietnamese($text);
        $alias = rtrim($alias, '-');

        return $alias . '-' . uniqid();
    }

    public static function createUniqueFilename($pathFile, $filename)
    {
        $filename = strtolower($filename);
        $fileExtension = self::getExtension($filename);
        $filename = str_replace('.' . $fileExtension, '', $filename);

        $fullFilePath = $pathFile . DIRECTORY_SEPARATOR . $filename . '.' . $fileExtension;

        if (File::exists($fullFilePath)) {
            // Generate token for image
            $imageToken = substr(sha1(mt_rand()), 0, 5);
            $filename .= '-' . $imageToken;
        }

        return $filename . '.' . $fileExtension;
    }

    public static function getExtension($filename)
    {
        return strtolower(substr(strrchr($filename, '.'), 1));
    }

    public function moveFile($oldDir, $newDir, $fileName)
    {
        if (is_file($oldDir . $fileName)) {
            if (!is_dir($newDir)) {
                mkdir($newDir, 0777, TRUE);
            }

            rename($oldDir . $fileName, $newDir . $fileName);
        }
    }

    public static function getURL($url)
    {
        $regex = '/https?\:\/\/[^\" ]+/i'; // SCHEME

        if (!preg_match($regex, $url, $m)) {
            return 'http://' . $url;
        }

        return $url;
    }

    public static function resizeImage($target_file, $new_height = 100)
    {
        // $image is the uploaded image
        list($width, $height) = getimagesize($target_file);

        //setup the new size of the image
        $ratio = $width / $height;
        $new_width = $new_height * $ratio;

        // resample the image
        $new_image = imagecreatetruecolor($new_width, $new_height);
        $old_image = imagecreatefromjpeg($target_file);
        imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        //output
        imagejpeg($new_image, $target_file, 100);

        imagedestroy($new_image);
        imagedestroy($old_image);
    }

    public static function removeDirRecursive($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file)
                continue;

            if (is_dir("$dir/$file")) {
                self::removeDirRecursive("$dir/$file");
            } else {
                @unlink("$dir/$file");
            }
        }

        @rmdir($dir);
    }

    public static function launchBackgroundProcess($command, $logPath, $pidPath)
    {
        if (self::isWindows()) { // Windows
            $commandLine = sprintf('%s 1>> %s 2>&1', $command, $logPath);

            pclose(popen('start /b ' . $commandLine, 'r'));
        } else { // Some sort of UNIX
            exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $command, $logPath, $pidPath));
        }

        return TRUE;
    }

    public static function isWindows()
    {
        if (PHP_OS == 'WINNT' || PHP_OS == 'WIN32') {
            return TRUE;
        }

        return FALSE;
    }

    public static function getPlainText($text, $length = NULL, $more = '...')
    {
        $text = strip_tags($text);
        $text = str_replace(["\r\n", "\n"], " ", $text);

        if (str_word_count($text, 0) > $length && $length) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$length]) . $more;
        }

        return $text;
    }

    public static function convertCurrency($number)
    {
        return number_format($number, 2, ',', '.');
    }

    public static function calculateDiscount($price, $regularPrice)
    {
        $discountPercent = 0;

        if ($regularPrice > 0) {
            $discountPercent = ($price - $regularPrice) / $regularPrice * 100;
        }

        return round($discountPercent, 1) . '%';
    }

    public static function getFiles($folderPath)
    {
        $files = File::allFiles($folderPath);

        $bannerFiles = [];
        foreach ($files as $file) {
            $bannerFiles[$file->getFileName()] = $file->getFileName() . '?t=' . filemtime($folderPath . $file->getFileName());
        }

        return $bannerFiles;
    }

    public static function scaleImage($imageData, $imageDes, $size = [])
    {
        $manager = new ImageManager();
        $imageMake = $manager->make($imageData)->resize($size[0], NULL, function ($constraint) {
            $constraint->aspectRatio();
        });

        $color = $manager->canvas($size[0], $size[1]);
        $color->fill('#ffffff');
        $color->insert($imageMake, 'center')->crop($size[0], $size[1])->save($imageDes);
    }

    public static function getDomainFromUrl($url)
    {
        $parts = parse_url($url);

        $normalizeUrl = $parts['scheme'] . '://' . $parts['host'];

        if (isset($parts['port']) && $parts['port']) {
            $normalizeUrl .= ':' . $parts['port'];
        }

        return $normalizeUrl;
    }

    public static function checkInRangeDate($date, $day, $yearBoth = TRUE)
    {
        if ($date) {
            if ($yearBoth) {
                $date = explode('-', $date);
                $date = sprintf('%s-%s-%s', date('Y'), $date[1], $date[2]);
            }

            $currentDate = strtotime(date('Y-m-d', time()));
            $startDate = strtotime($date);
            $dateDiff = $startDate - $currentDate;

            $dayNumber = round($dateDiff / (60 * 60 * 24));
            if ($dayNumber <= $day && $dayNumber >= 0) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public static function getWorkDay($date)
    {
        $workDayTxt = '';
        if ($date) {
            $currentDate = strtotime(date('Y-m-d', time()));
            $startDate = strtotime($date);
            $dateDiff = $currentDate - $startDate;
            $dayNumber = round($dateDiff / (60 * 60 * 24));

            if ($dayNumber >= 365) {
                $workDayTxt .= floor($dayNumber / 365) . ' năm ';
                $dayNumber = $dayNumber - floor($dayNumber / 365) * 365;
            }

            if ($dayNumber >= 30) {
                $workDayTxt .= floor($dayNumber / 30) . ' tháng ';
            } else {
                $workDayTxt .= $dayNumber . ' ngày';
            }

            $workDayTxt = '(' . trim($workDayTxt) . ')';
        }

        return $workDayTxt;
    }

    public static function getStatus($id = NULL)
    {
        $statusArray = [
            Constant::STATUS_INACTIVE => 'Inactive',
            Constant::STATUS_ACTIVE => 'Active',
        ];

        if ($id !== NULL) {
            return isset($statusArray[$id]) ? $statusArray[$id] : 'N/a';
        }

        return $statusArray;
    }

    public static function getJobStatus($allFlag = FALSE, $id = NULL)
    {
        $statusArray = [];

        if ($allFlag) {
            $statusArray[''] = 'All';
        }

        $statusArray['deleted'] = 'Deleted';
        $statusArray['completed'] = 'Completed';
        $statusArray['failed'] = 'Failed';
        $statusArray['submitted'] = 'Submitted';
        $statusArray['paused'] = 'Paused';
        $statusArray['resumed'] = 'Resumed';
        $statusArray['restart'] = 'Restart';
        $statusArray['Rendering'] = 'Rendering';
        $statusArray['Active'] = 'Active';

        if ($id !== NULL) {
            return isset($statusArray[$id]) ? $statusArray[$id] : ucfirst($id);
        }

        return $statusArray;
    }

    public static function getJobOutputStatus($allFlag = FALSE, $arrayFlag = TRUE, $id = NULL)
    {
        $statusArray = [];

        if ($allFlag) {
            $statusArray[''] = 'All';
        }

        $statusArray['waiting'] = 'Waiting';
        $statusArray['completed'] = 'Completed';

        if ($arrayFlag === FALSE) {
            return isset($statusArray[$id]) ? $statusArray[$id] : '';
        }

        return $statusArray;
    }

    public static function convertLocalTimezone($time, $timezone)
    {
        if ($time && $time != '0001-01-01UTC00:00:000') {
            $time = date('Y-m-d H:i:s', strtotime($time . ' +' . $timezone . ' hour'));
        }

        if ($time == '0001-01-01UTC00:00:000') {
            $time = '-';
        }

        return $time;
    }

    public static function timeElapsedString($datetime, $full = FALSE)
    {
        $now = new \DateTime();
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public static function convertToReadableSize($size)
    {
        $base = log($size) / log(1024);
        $suffix = ['', 'Kb', 'Mb', 'Gb', 'Tb'];
        $f_base = floor($base);

        return round(pow(1024, $base - floor($base)), 1) . ' ' . $suffix[$f_base];
    }

    public static function checkUserRoleEnv($roleName)
    {
        $user = Auth::guard('admin')->user();
        $roleName = env($roleName);
        $roleArray = explode(',', $roleName);

        return in_array($user->roles, $roleArray);
    }

    public static function getPromotionCouponType($allFlag = FALSE, $id = NULL)
    {
        $couponType = [
            'discount' => 'Discount',
            'bonus' => 'Bonus',
        ];

        if ($allFlag) {
            $couponType = ['' => 'All'] + $couponType;
        }

        if ($id) {
            return isset($couponType[$id]) ? $couponType[$id] : '';
        }

        return $couponType;
    }

    public static function getValueCouponType($allFlag = FALSE, $id = NULL)
    {
        $valueType = [
            'percentage' => 'Percentage',
            'fixed_amount' => 'Fixed Bonus',
        ];

        if ($allFlag) {
            $valueType = ['' => 'All'] + $valueType;
        }

        if ($id) {
            return isset($valueType[$id]) ? $valueType[$id] : '';
        }

        return $valueType;
    }

    public static function getPromotionDependValue($string)
    {
        $dependValueArray = [
            [
                'from' => '',
                'to' => '',
                'value' => '',
            ],
        ];

        if ($string) {
            $dependValueArray = [];

            $dependValues = explode(',', $string);
            foreach ($dependValues as $item) {
                $dependValueTmp = [
                    'from' => '',
                    'to' => '',
                    'value' => '',
                ];

                $itemArray = explode(':', $item);
                if (isset($itemArray[1])) {
                    $dependValueTmp['value'] = $itemArray[1];
                }

                $itemArray2 = explode('-', $itemArray[0]);
                $dependValueTmp['from'] = $itemArray2[0];
                if (isset($itemArray2[1])) {
                    $dependValueTmp['to'] = $itemArray2[1];
                }

                $dependValueArray[] = $dependValueTmp;
            }
        }

        return $dependValueArray;
    }

    public static function buildDepentPaymentText($dependArray)
    {
        $dependTextArray = [];
        foreach ($dependArray['from'] as $index => $item) {
            $dependTextTmp = '';
            if ($item) {
                $dependTextTmp .= $item;
            }

            if ($dependArray['to'][$index]) {
                $dependTextTmp .= '-' . $dependArray['to'][$index];
            }

            $dependTextTmp .= ':' . $dependArray['value'][$index];

            $dependTextArray[] = $dependTextTmp;
        }

        return implode(',', $dependTextArray);
    }

    public static function getUserLevel()
    {
        return $userLevelArray = [
            'tiny' => 'Tiny',
            'small' => 'Small',
            'standard' => 'Standard',
            'gold' => 'Gold',
            'platinum' => 'Platinum',
        ];
    }

    /**
     * @param RenderJob $job
     *
     * @return string
     */
    public static function getJobSelectedLayer($job)
    {
        $selectedLayerText = '';
        $renderJobParams = json_decode($job->params, TRUE);

        if ($renderJobParams['software'] == 'blender') {
            $selectedLayerText = 'Selected Layer: <b>' . $job['scene_name'] . '</b>';
        } elseif ($renderJobParams['software'] == 'maya') {
            $selectedLayerText = 'Selected Layer: <b>' . $renderJobParams['job_detail']['render_layer'] . '</b>';
        } elseif ($renderJobParams['software'] == 'houdini') {
            $selectedLayerText = 'Selected Render node: <b>' . $renderJobParams['job_detail']['render_node'] . '</b>';
        }

        return $selectedLayerText;
    }

    public static function getGiftTypes($allFlag = FALSE, $id = NULL)
    {
        $valueType = [
            'one_time' => 'One time',
            'forever' => 'Forever',
        ];

        if ($allFlag) {
            $valueType = ['' => 'All'] + $valueType;
        }

        if ($id) {
            return isset($valueType[$id]) ? $valueType[$id] : '';
        }

        return $valueType;
    }

    public static function getGiftConditions()
    {
        $valueType = [
            'all' => 'All',
            'user_level' => 'User Level',
            'user_credits' => 'User Credits',
            'user_spend_credits' => 'User Spend Credits',
            'render_job' => 'Render Job',
            'payment' => 'Payment',
        ];

        return $valueType;
    }

    public static function getRenderTaskStt($renderTaskStt)
    {
        $renderTaskSttArray = [
            'Total Frames' => [
                'color' => 'rgba(0, 142, 242, 0.4)',
                'value' => 0,
            ],
        ];
        if(!empty($renderTaskStt)){
            foreach ($renderTaskStt as $name => $value) {
                $renderTaskSttArray['Total Frames']['value'] += $value;
                $normalizeName = mb_strtolower($name);
    
                switch ($normalizeName) {
                    case 'completed':
                        $renderTaskSttArray[$name] = [
                            'color' => 'rgba(0, 178, 86, 1)',
                            'value' => $value,
                        ];
                        break;
    
                    case 'waiting':
                        $renderTaskSttArray[$name] = [
                            'color' => 'rgba(255, 87, 34, 1)',
                            'value' => $value,
                        ];
                        break;
    
                    case 'rendering':
                        $renderTaskSttArray[$name] = [
                            'color' => 'rgba(255, 193, 7, 1)',
                            'value' => $value,
                        ];
                        break;
    
                    case 'paused':
                        $renderTaskSttArray['Suspended'] = [
                            'color' => 'rgba(255, 157, 5, 1)',
                            'value' => $value,
                        ];
                        break;
    
                    case 'failed':
                        $renderTaskSttArray[$name] = [
                            'color' => 'rgb(244, 67, 54)',
                            'value' => $value,
                        ];
                        break;
                }
            }
        }

        $html = '<div class="clearfix row-total-task-status">';
        foreach ($renderTaskSttArray as $key => $item) {
            $html .= sprintf('<div class="col-xs-6 col-sm-6 col-md-2">%s: %s</div>', $key, $item['value']);
        }
        $html .= '</div>';

        return $html;
    }

    public static function renderHistoryAnalyze($history,$email,$fname,$fpath)
    {
        $arr_his = explode(',',$history);
        $html = '';
        if(!empty($arr_his)){
            foreach($arr_his as $his){
                $html .= sprintf(' <a href="javascript:void(0)" class="btn-view-scene-analyze" data-action="%s" data-id="%s" data-email="%s" data-fname="%s" data-fpath="%s">%s</a>, ', route('scene.analyze'), $his,$email, $fname, $fpath,$his);
            }
        }
        return $html;
    }
}
