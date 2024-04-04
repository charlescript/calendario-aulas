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



CREATE TABLE tb_turma (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao VARCHAR(500)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO tb_turma ( nome, descricao ) VALUES ( "DS1 - NOITE 3º CICLO", "Turma responsável por aprender as bases dos sistemas." ),
												( "DS2 - NOITE 4º CICLO", "Turma responsável por aprender diagramas UML");

CREATE TABLE tb_turma_users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_turma INT NOT NULL,
    id_user INT NOT NULL,
    CONSTRAINT fk_turma_user_turma FOREIGN KEY (id_turma) REFERENCES tb_turma(id) ON DELETE CASCADE,
    CONSTRAINT fk_turma_user_user FOREIGN KEY (id_user) REFERENCES tb_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE tb_turma_event (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_turma INT NOT NULL,
    id_event INT NOT NULL,
    CONSTRAINT fk_turma_event_turma FOREIGN KEY (id_turma) REFERENCES tb_turma(id) ON DELETE CASCADE,
    CONSTRAINT fk_turma_event_event FOREIGN KEY (id_event) REFERENCES tb_events(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


show tables;

select * from tb_users;
select * from tb_events;
select * from tb_event_user;
select * from tb_turma;
select * from tb_turma_event;

insert into tb_turma_event (id_turma, id_event) VALUES( 1, 94);
insert into tb_turma_event (id_turma, id_event) VALUES( 1, 95);


SELECT evt.id, evt.title, evt.description, evt.color, evt.start, evt.end,
    usr.id as user_id, usr.nome as user_nome, usr.email as user_email, tm.id as id_turma, tm.nome as nome_turma, tm.descricao as descricao_turma
    FROM tb_events as evt
        INNER JOIN tb_event_user AS ev_us ON ev_us.id_event = evt.id
            INNER JOIN tb_users as usr ON usr.id = ev_us.id_user
                INNER JOIN tb_turma_event as tm_ev ON tm_ev.id_event = evt.id
                    INNER JOIN tb_turma as tm ON tm.id = tm_ev.id_turma
                WHERE usr.id = 2;

desc tb_users;
INSERT INTO tb_users (nome, cpf, email, senha, senha_crip, nivel, dt_cadastro)
	VALUES('Charles', '123456789', 'rocha_charles@teste.com','123', MD5('123') ,'admin', now());
    
INSERT INTO tb_users (nome, cpf, email, senha, senha_crip, nivel, dt_cadastro)
	VALUES('Rodrigo Salgado', '123456789', 'rodrigo@teste.com','123', MD5('123') ,'admin', now());
    
INSERT INTO tb_users (nome, cpf, email, senha, senha_crip, nivel, dt_cadastro)
	VALUES('Paulo Candido', '123456789', 'paulo@teste.com','123', MD5('123') ,'admin', now());
    
INSERT INTO tb_users (nome, cpf, email, senha, senha_crip, nivel, dt_cadastro)
	VALUES('Simone database', '123456789', 'database@teste.com','123', MD5('123') ,'admin', now());


select * from tb_users;