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
