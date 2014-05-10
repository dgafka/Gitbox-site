ALTER TABLE banned_ip DROP COLUMN expire_date;
ALTER TABLE banned_ip DROP COLUMN description;
ALTER TABLE banned_ip
    ALTER COLUMN create_date TYPE TIMESTAMP;