-- Sprinkles schema
-- MySQL version

create table sessions (
  session_id bigint primary key auto_increment,
  creation_date timestamp,
  username varchar(255)
);

create table site_links (
  url text
);

create table site_settings (
  background_color varchar(255),
  logo_url text,
  contact_email varchar(255),
  contact_phone varchar(255),
  contact_address text,
  map_url text,
  faq_type varchar(255)
);

insert into site_settings values ();

create table users (
  username varchar(255) not null,
  password varchar(255) not null
);

insert into users (username, password) values ('ezra', 'knockknock');