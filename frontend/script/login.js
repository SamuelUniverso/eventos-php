
function getSession()
{
    return localStorage.getItem('session');
}

function setSession(session)
{
    localStorage.setItem('session', session);
}

function dropSession()
{
    localStorage.removeItem('session');
}

function isSession()
{
    return getSession();
}
/**
 * @method authenticate
 */
function authenticate(usuario, senha)
{
    fetch(`http://localhost:8081/authenticate.php`, {
        method: 'POST',
        body: `{ "username": "`+usuario+`", "password": "`+senha+`" }`
    })
    .then(
        res => res.json()
    )
    .then(
        function(res) {
            dropSession();

            try
            {
                if(!res.session)
                {
                    alert("Login incorreto")
                    return;
                }
            }
            catch(error)
            {
                console.log(error);
            }
            
            setSession(res.session);

            if(isSession()) {
                location.href = "home.html";
            }
        }
    )
    .catch(error => console.log(error));
}

/**
 * @method deauthenticate
 */
function deauthenticate()
{
    dropSession();

    if(!isSession()) {
        location.href = "index.html";
    }
}

function validateSession()
{
    if(!isSession()) {
        location.href = "index.html";
    } 
}
