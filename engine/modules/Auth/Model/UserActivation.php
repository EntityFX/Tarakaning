<?php
class UserActivation extends DBConnector   
{
    const TBL_USER_ACTIVATION   = "USER_ACTIV";
    
    const MIN_ASCII_NUM         = 0x30;
    const MAX_ASCII_NUM         = 0x39;
    
    const MIN_ASCII_CHR         = 0x61;
    const MAX_ASCII_CHR         = 0x66;
    
    const KEY_LENGTH            = 30;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function createActivationKey($userIdentificator)
    {
        $userIdentificator = (int)$userIdentificator; 
        if (!$this->checkIfExsists($userIdentificator))
        {
            $activationKey = $this->generateKeyString(self::KEY_LENGTH);
            try
            {
                $this->_sql->insert(
                    self::TBL_USER_ACTIVATION,
                    new ArrayObject(array(
                        $userIdentificator,
                        $activationKey,
                        date(DATE_ISO8601)
                    ))
                );  
            }
            catch (Exception $insertException)
            {
                return null;
            }
            return $this->getKeyForSending($activationKey,$userIdentificator); 
        }
        return null;
    }
    
    private function checkIfExsists($userIdentificator)
    {
        $userIdentificator = (int)$userIdentificator;
        $this->_sql->selFieldsWhere(self::TBL_USER_ACTIVATION,"USER_ID = $userIdentificator","USER_ID");
        return $this->_sql->getResultRows() == null ? false : true;
    }
    
    private function deleteActivationKey($userIdentificator)
    {
        try
        {
            $this->_sql->delete(self::TBL_USER_ACTIVATION,'USER_ID = $userIdentificator');
        }
        catch(Exception $sqlException)
        {
            return false;
        }
        return true;
    }                    
    
    private function validateActivationKey($activationData)
    {
        $mid = (int)floor(self::KEY_LENGTH / 2); // a % 2 ??
        $keyLength = strlen($activationData);
        
        $firstKeyPart = substr($activationData,0,$mid);
        $secondKeyPart = substr($activationData,$keyLength - (self::KEY_LENGTH - $mid));
        
        $userIDEncodedString = substr($activationData,$mid,$keyLength - self::KEY_LENGTH);
        
        $decodedKey = $firstKeyPart.$secondKeyPart;
        if (strlen($decodedKey) == self::KEY_LENGTH)
        {
            $userEncodedID = $this->decodeUserID($userIDEncodedString);
            return $userEncodedID == 0 ? false :  
                array(
                    'activationKey' => $firstKeyPart.$secondKeyPart,
                    'userID' => $userEncodedID
                );      
        }
        else
        {
            return false;
        }
    }
    
    public function activateUserKey($activationData)
    {
        $decodedData = $this->validateActivationKey($activationData);
        if ($decodedData !== false)
        {
            $this->_sql->selAllWhere(self::TBL_USER_ACTIVATION,"USER_ID = $decodedData[userID]");
            $records = $this->_sql->getResultRows();
            if ($records != null)
            {
                $currentDateTime = new DateTime(); //current date time
                $expiryDateTime = new DateTime($records['EXPRY_DT']); //expiry date time   
                var_dump($currentDateTime,$expiryDateTime);
                var_dump($currentDateTime->diff($expiryDateTime));
                if ($currentDateTime->getTimestamp()-$expiryDateTime->getTimestamp()<=604800)    
                {
                    echo "good";  
                }
                else
                {
                    echo "not good"; 
                }
            }
        }
        else
        {
            return false;
        }
    }
    
    public function generateKeyString($length)
    {
        $length=(int)$length;
        $resString = "";
        for($it=0;$it<$length;++$it)
        {
            $selector = mt_rand(0,1);
            switch ($selector)
            {
                case 0:
                    $resString .= chr(mt_rand(self::MIN_ASCII_NUM,self::MAX_ASCII_NUM));
                    break;
                case 1:
                    $resString .= chr(mt_rand(self::MIN_ASCII_CHR,self::MAX_ASCII_CHR));
                    break;
                default:
                    break;
            }
        }
        return $resString;
    }
    
    public function getKeyForSending($activationKey, $userID)
    {
        $mid = (int)floor(self::KEY_LENGTH / 2);
        $firstKeyPart = substr($activationKey,0,$mid);
        $secondKeyPart = substr($activationKey,$mid);
        $userIDEncoded = $this->encodeUserId($userID);
        return $firstKeyPart.$userIDEncoded.$secondKeyPart;
    }
    
    private function encodeUserId($userID)
    {
        $strUserID = (string)$userID;
        for($strind=0;$strind<strlen($strUserID);++$strind)
        {
            $userIEncoded .= dechex(ord($strUserID[$strind]));
        }
        return $userIEncoded;
    }
    
    private function decodeUserID($encodedUserID)
    {
        $strLen = strlen($encodedUserID);
        for($it=0; $it<$strLen;$it+=2)
        {
            $decodedStr .= chr(hexdec(substr($encodedUserID,$it,2)));
        }
        return (int)$decodedStr;
    }
}
?>
