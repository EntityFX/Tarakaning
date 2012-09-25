DROP VIEW IF EXISTS view_SubscribesUserNick;
CREATE VIEW view_SubscribesUserNick AS
SELECT 
    SR.SUBSCR_RQST_ID AS ID, 
    SR.USER_ID AS UserID, 
    SR.PROJ_ID AS ProjectID, 
    SR.RQST_TM AS RequestTime, 
    U.NICK AS NickName
FROM
    SUBSCR_RQST SR
LEFT JOIN `USER` U
    ON SR.USER_ID = U.USER_ID;