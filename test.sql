
-- hpapi:ts,ky,em

  SELECT
    <<ts>> AS `now` 
   ,`id` AS `userID` 
   ,`active` AS `userActive` 
   ,`password_hash` AS `passwordHash` 
   ,`email` IS NOT NULL AS `emailFound` 
   ,`email_verified` AS `emailVerified` 
   ,`key`
   ,`key_release` AS `respondWithKey`
   ,`hpapi_user`.`remote_addr_pattern` AS `remoteAddrPattern`
  FROM `hpapi_user`
  WHERE `email`=<<em>>
    AND (
         (`key`=<<ky>> AND `key_expired`='0')
      OR (`key_release`>0 AND UNIX_TIMESTAMP(`key_release_until`)><<ts>>)
    )
  LIMIT 0,1
  ;
