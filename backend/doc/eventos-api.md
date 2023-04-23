# Eventos-API Documentation

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
