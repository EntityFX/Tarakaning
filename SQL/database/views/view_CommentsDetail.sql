CREATE VIEW view_CommentsDetail AS 
SELECT
    IC.ITEM_CMMENT_ID AS `ID`,
    IC.ITEM_ID AS `ItemID`,
    IC.USER_ID AS `UserID`,
    IC.CRT_TM AS `Time`,
    IC.CMMENT AS `Comment`,
    U.NICK AS `Nick`
FROM
    (ITEM_CMMENT IC
    LEFT JOIN USER U
        ON ((IC.USER_ID = U.USER_ID)));