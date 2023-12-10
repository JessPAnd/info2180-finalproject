-- Host: localhost    Database: dolphin_crm
-- Server version   8.0.32-log

/*!40101 SET NAMES utf8 */;
SET TIME_ZONE='-05:00' ;

DROP DATABASE IF EXISTS dolphin_crm;
CREATE DATABASE dolphin_crm;
USE dolphin_crm;

-- Table structure for table users
CREATE TABLE users (
    id int(3) NOT NULL auto_increment,
    firstname varchar(35) NOT NULL default '',
    lastname varchar(20) NOT NULL default '',
    `password` varchar(32) NOT NULL default '',
    email varchar(25) NOT NULL default '',
    `role` varchar(11) NOT NULL default '',
    created_at Datetime default NOW(),
    PRIMARY KEY  (id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Insert initial data into the users table
LOCK TABLES users WRITE;
/*!40000 ALTER TABLE users DISABLE KEYS */;
INSERT INTO users(firstname, lastname, password, email, role) VALUES
    ('Michael', 'Benjamin', MD5('password123'), 'admin@project2.com', 'admin'),
    ('Jessica', 'Anderson', MD5('password112'), 'admin2@project2.com', 'admin2'),
    ('Ivanna', 'Buckley', MD5('password113'), 'admin3@project2.com', 'admin3'),
    ('Chavon', 'Forrester', MD5('password114'), 'admin4@project2.com', 'admin4'),
    ('Peter-Jon', 'Lawrence', MD5('password115'), 'admin5@project2.com', 'admin5');
/*!40000 ALTER TABLE users ENABLE KEYS */;
UNLOCK TABLES;

-- -- Create a new user
-- CREATE USER 'DolphinAdmin'@'localhost' IDENTIFIED BY 'password123';

-- -- Grant privileges to the user
-- GRANT ALL PRIVILEGES ON dolphin_crm.* TO 'DolphinAdmin'@'localhost';

-- -- Apply the changes
-- FLUSH PRIVILEGES;


-- Table structure for table contacts
CREATE TABLE contacts (
    id int(3) NOT NULL auto_increment,
    title varchar(20) NOT NULL default '',
    firstname varchar(20) NOT NULL default '',
    lastname varchar(20) NOT NULL default '',
    email varchar(30) NOT NULL default '',
    telephone varchar(20) NOT NULL default '',
    company varchar(20) NOT NULL default '',
    `type` enum('Sales Lead','Support') NOT NULL default 'Sales Lead',
    assigned_to int(11) default NULL,
    created_by int(11) default Null,
    created_at Datetime default NOW(),
    updated_at Datetime default NOW(),
    PRIMARY KEY  (id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Insert initial data into the contacts table
LOCK TABLES contacts WRITE;
/*!40000 ALTER TABLE contacts DISABLE KEYS */;
INSERT INTO contacts(title, firstname, lastname, email, telephone, company, type, assigned_to, created_by) VALUES
    ('Host', 'Michael', 'Benjamin', 'Michael@project2.com', '18761212121', 'NovaTech', 'Sales Lead', 3, 3);
/*!40000 ALTER TABLE contacts ENABLE KEYS */;
UNLOCK TABLES;

-- Table structure for table notes
CREATE TABLE notes (
    id int(3) NOT NULL auto_increment,
    contact_id varchar(11) NOT NULL default '',
    comment text NOT NULL,
    created_by int(11) default Null,
    created_at Datetime default NOW(),
    PRIMARY KEY  (id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Insert initial data into the notes table
LOCK TABLES notes WRITE;
/*!40000 ALTER TABLE notes DISABLE KEYS */;
INSERT INTO notes(contact_id, comment, created_by) VALUES
    ('2', 'Customer successfully added', 3),
    ('3', 'Customer addition not successful', 9);
/*!40000 ALTER TABLE notes ENABLE KEYS */;
UNLOCK TABLES;
