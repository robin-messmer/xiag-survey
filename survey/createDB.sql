create database survey;
use survey;

create table question(
	id int not null AUTO_INCREMENT PRIMARY key,
    question varchar(200) not null
);

create table poll(
	id int not null AUTO_INCREMENT PRIMARY key,
    link varchar(20) not null,
    question_id int,
    FOREIGN KEY (question_id)
        REFERENCES question(id)
    On delete cascade
);


create table answer(
	id int not null AUTO_INCREMENT PRIMARY key,
    answer varchar(30) not null,
	poll_id int,
	FOREIGN KEY (poll_id)
        REFERENCES poll(id)
    On delete cascade
);

create table result(
	id int not null AUTO_INCREMENT PRIMARY key,
    name varchar(60) not null,
    answer_id int not null,
    FOREIGN KEY (answer_id)
        REFERENCES answer(id)
    On delete cascade
);

create table voter(
	id int not null AUTO_INCREMENT PRIMARY key,
    voter_ip varchar(30) not null,
    voter_user_agent varchar(300) not null, 
    link_id int not null,
    FOREIGN KEY (link_id)
        REFERENCES poll(id)
    On delete cascade
);

create view answer_res_help as
select a.poll_id, CONCAT('"',a.answer,'":[', COALESCE( GROUP_CONCAT(CONCAT('"',r.name,'"')), '' ), ']' ) as answer_res  from answer a
left join result r on a.id = r.answer_id
group by a.id;


create view polls as
select p.link, q.question, CONCAT('{',group_concat(ar.answer_res),'}') answer_res from poll p
inner join question q on q.id = p.question_id
inner join answer_res_help ar on ar.poll_id = p.id
group by p.id;

