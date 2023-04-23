# Eventos-API Documentation

## Login
**POST: /authenticate.php**

    {
	    "username": "<username>",
	    "password: "****"
    }
Response:

    {
        "session": true,
        "username": "admin"
    }

## Pessoas
**GET: /api/pessoa/{$id}**

    {
	    "id": 1,
	    "nome: "Fulano",
	    "cpf": "123456789AB"
    }

**GET: /api/pessoa/cpf/{$cpf}**

    {
	    "id": 1,
	    "nome: "Fulano",
	    "cpf": "123456789AB"
    }

**GET: /api/pessoa/all/**

    [{
	    "id": 1,
	    "nome: "Fulano",
	    "cpf": "123456789AB"
    },
    {
	    "id": 2,
	    "nome: "Beltrano",
	    "cpf": "987654321AB"
    }]
   
**POST: /api/pessoa/**

    {
	    "nome: "Fulano",
	    "cpf": "123456789AB"
    }

**PUT: /api/pessoa/**

    {
	    "id": 1,
	    "nome: "Fulano",
	    "cpf": "123456789AB"
    }

**DELETE: /api/pessoa/{$id}**

## Usuarios
**GET: /api/usuario/{$id}**

    {
	    "id": 1,
	    "usuario: "admin",
	    "senha": "<sha256-hash>"
    }

**GET: /api/usuario/all/**

    [{
	    "id": 1,
	    "usuario: "admin",
	    "senha": "<sha256-hash>"
    },
    {
	    "id": 2,
	    "usuario: "operator",
	    "senha": "<sha256-hash>"
    }]
   
**POST: /api/usuario/**

    {
	    "usuario: "admin",
	    "senha": "****"
    }

**PUT: /api/usuario/**

    {
	    "id": 1,
	    "usuario: "admin",
	    "senha": "****"
    }

**DELETE: /api/usuario/{$id}**

## Eventos
**GET: /api/evento/{$id}**

    {
	    "id": 1,
	    "nome": "Evento",
	    "datahora": "yyyy-mm-dd hh24:mi:ss"
    }

**GET: /api/evento/all/**

    [{
	    "id": 1,
	    "nome": "Evento 1",
	    "datahora": "yyyy-mm-dd hh24:mi:ss"
    },
    {
	    "id": 2,
	    "nome": "Evento 2",
	    "datahora": "yyyy-mm-dd hh24:mi:ss"
    }]
   
**POST: /api/evento/**

    {
	    "evento: "Evento",
	    "datahora": "yyyy-mm-dd hh24:mi:ss"
    }

**PUT: /api/evento/**

    {
	    "id": 1,
	    "nome": "Evento",
	    "datahora": "yyyy-mm-dd hh24:mi:ss"
    }

**DELETE: /api/evento/{$id}**

## Inscrições
**GET: /api/inscricao/{$id}**

    {
	    "id": 1,
	    "fk_evento": 1,
		"fk_pessoa": 1,
	    "presenca": false
    }

**GET: /api/inscricao/all/**

    [{
	    "id": 1,
	    "fk_evento": 1,
		"fk_pessoa": 1,
	    "presenca": false
    },
    {
	    "id": 2,
	    "fk_evento": 1,
		"fk_pessoa": 2,
	    "presenca": true
    }]
   
**POST: /api/inscricao/**

    {
	    "fk_evento": 1,
		"fk_pessoa": 1,
	    "presenca": false
    }

**PUT: /api/inscricao/**

    {
	    "id": 1,
	    "fk_evento": 1,
		"fk_pessoa": 1,
	    "presenca": false
    }

**DELETE: /api/inscricao/{$id}**


