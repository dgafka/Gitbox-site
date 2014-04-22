INSERT INTO user_group(
            permissions, description)
    VALUES (1, 'Regular user');
INSERT INTO user_description(
            description, registration_date, ban_date, ip, hit)
    VALUES ('I am a test user.', '2014-04-12', null, '', 1);
INSERT INTO user_account(
            id_group, id_description, login, password, status, email)
    VALUES (1, 1, 'test', 'test', 'A', 'test@email.com');
INSERT INTO user_account(
            id_group, id_description, login, password, status, email)
    VALUES (1, 1, 'test2', 'test2', 'A', 'test@email2.com');
INSERT INTO user_account(
            id_group, id_description, login, password, status, email)
    VALUES (1, 1, 'test3', 'test3', 'A', 'test@email3.com');
INSERT INTO module(
    name, description)
VALUES ('movTube', 'Video module');
INSERT INTO module(
    name, description)
VALUES ('blog', 'Blog module');
INSERT INTO module(
    name, description)
VALUES ('virtualDrive', 'File module');
INSERT INTO module(
    name, description)
VALUES ('none', 'No module');
INSERT INTO category(
            name, description)
    VALUES ('Sport', 'Sportowa kategoria, ktora jest zajebista!');
INSERT INTO menu(
            id_category, id_module, parent, title, status, sort, expire,
            id_user)
    VALUES (1, 4, null, 'Strona Główna', 'A', 1, null,
            2);
INSERT INTO menu(
            id_category, id_module, parent, title, status, sort, expire,
            id_user)
    VALUES (1, 4, null, 'Moje konto', 'A', 2, null,
            2);
INSERT INTO menu(
            id_category, id_module, parent, title, status, sort, expire,
            id_user)
    VALUES (1, 4, null, 'Wyszukiwarka', 'A', 3, null,
            2);
INSERT INTO content(
            id_user, id_menu, status, title, header, description, create_date,
            hit, expire, last_modification_date, rate, type, id_category)
    VALUES (2, 2, 'A', 'Jestę testowy content', 'Header', 'Testowy content dla strony glownej pobrany z bazy', '2014-04-17',
            5, null, '2014-04-17', 4, 'A', 1);
INSERT INTO user_modules(
    id_user, id_module)
VALUES (2, 1);
INSERT INTO user_modules(
    id_user, id_module)
VALUES (2, 2);
INSERT INTO user_modules(
    id_user, id_module)
VALUES (2, 3);