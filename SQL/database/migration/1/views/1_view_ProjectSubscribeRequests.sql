DROP VIEW IF EXISTS view_ProjectSubscribeRequests;
CREATE VIEW view_ProjectSubscribeRequests
AS 
	SELECT 
         SR.PROJ_ID AS 'ProjectID'
         ,count(SR.PROJ_ID) AS 'CountRequests'
    FROM
         SUBSCR_RQST SR
    GROUP BY
         SR.PROJ_ID