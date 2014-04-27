ALTER TABLE content
    ALTER COLUMN status SET DEFAULT 'A',
    ALTER COLUMN header DROP NOT NULL,
    ALTER COLUMN create_date TYPE TIMESTAMP,
    ALTER COLUMN hit SET DEFAULT 0,
    ALTER COLUMN last_modification_date TYPE TIMESTAMP,
    ALTER COLUMN type SET DEFAULT 1; -- 1 publiczny

ALTER TABLE menu
    ALTER COLUMN status SET DEFAULT 'A';

DELETE FROM menu;

INSERT INTO menu (id_module, title)
    VALUES (
        (SELECT id FROM module WHERE module.name = 'blog'),
        'blog'
    );