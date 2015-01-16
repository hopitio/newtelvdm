CREATE TABLE nt_user(
    pk_user INT PRIMARY KEY AUTO_INCREMENT,
    c_name VARCHAR(255),
    c_representer VARCHAR(255),
    c_email VARCHAR(255),
    c_phone_no VARCHAR(20),
    c_password VARCHAR(255),
    c_deleted TINYINT DEFAULT 0,
    c_sort INT,
    c_is_admin TINYINT DEFAULT 0,
    c_account VARCHAR(255)
);

CREATE UNIQUE INDEX unq_account ON nt_user(c_account);

ALTER TABLE appointments ADD is_approved TINYINT DEFAULT 0;
ALTER TABLE appointments ADD owner_id INT DEFAULT 1;

CREATE TABLE nt_attendiees(
    fk_appointment INT NOT NULL,
    fk_user INT NOT NULL
);

CREATE UNIQUE INDEX unq_att ON nt_attendiees(fk_appointment, fk_user);

INSERT INTO  `nt_user` (
    `pk_user` ,
    `c_name` ,
    `c_representer` ,
    `c_email` ,
    `c_phone_no` ,
    `c_password` ,
    `c_deleted` ,
    `c_sort` ,
    `c_is_admin` ,
    `c_account`
)
VALUES (
    NULL ,  'Quản trị hệ thống',  'Quản trị hệ thống',  'admin@gmail.com',  '01666244670',  'e10adc',  '0',  '1',  '1',  'admin'
);