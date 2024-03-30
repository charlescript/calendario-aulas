show databases;
use projeto_tpe;

show tables;
select * from tb_usuarios;

create table tb_events (
	id int primary key auto_increment,
    title varchar(220) not null,
    description varchar(500),
    color varchar(45),
    start datetime,
    end datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE tb_users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cpf VARCHAR(20) NOT NULL,
    email VARCHAR(50) NOT NULL,
    senha VARCHAR(35) NOT NULL,
    senha_crip VARCHAR(150) NOT NULL,
    nivel VARCHAR(20) NOT NULL,
    dt_cadastro DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


create table tb_event_user (
	id int auto_increment primary key ,
    id_event int not null,
    id_user int not null,
    CONSTRAINT `fk_event_user` FOREIGN KEY (`id_event`) REFERENCES `tb_events` (`id`),
    CONSTRAINT `fk_user_event` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

truncate tb_event_user;


show tables;
select * from tb_events;
select * from tb_users;
select * from tb_event_user;




INSERT INTO tb_users (nome, cpf, email, senha, senha_crip, nivel, dt_cadastro) VALUES
('João Silva', '123.456.789-00', 'joao@example.com', 'senha123', 'crypted_password_1', 'admin', NOW()),
('Maria Oliveira', '987.654.321-00', 'maria@example.com', 'senha456', 'crypted_password_2', 'user', NOW()),
('Pedro Santos', '111.222.333-44', 'pedro@example.com', 'senha789', 'crypted_password_3', 'user', NOW()),
('Ana Souza', '555.666.777-88', 'ana@example.com', 'senhaabc', 'crypted_password_4', 'user', NOW());

ALTER TABLE tb_event_user AUTO_INCREMENT = 21;

INSERT INTO tb_event_user(id_event, id_user) VALUES (1,1), (2, 1),
 (3, 1),
 (4, 1),
 (5, 1),
 (6, 1),
 (7, 1),
 (8, 1),
 (9, 1),
 (10, 1),
 (11, 1),
 (12, 1),
 (13, 1),
 (14, 1),
 (15, 1),
 (16, 1),
 (17, 1),
 (18, 1),
 (19, 1),
 (20, 1);



