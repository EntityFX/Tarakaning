ALTER TABLE ITEM
    ADD COLUMN HOUR_REQ SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    ADD COLUMN HOUR_FACT SMALLINT UNSIGNED NOT NULL DEFAULT 0;