//public and private pages
//on logout present login form

const mainModule = makeMainModule();


function makeMainModule() {

    const baseApiUrl = "http://127.0.0.1:8000/api";
    const cookieName = "_Token";

    let userData = {
        name: null,
        email: null
    }

    function insertdiv(content, c) {
        return c === undefined ? "<div>" + content + "</div>" :
            "<div class=\"" + c + "\">" + content + "</div>";
    }

    function insertli(c) {
        return "<li class=\"item\">" + c + "</li>";
    }

    function refreshUser() {
        let userContainer = $(".username");
        for (let i = 0; i < userContainer.length; i++) {
            userContainer[i].text = userData.name;
        }
    }

    function setCookie(name, value, expires) {
        if (expires) {
            let date = new Date();
            date.setTime(expires);
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function eraseCookie(name) {
        document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }

    //---------------------

    function loadLoginForm() {
        $(".content").load("html/loginForm.html");
    }

    function loadOrders() {
        $(".content").load("html/loginForm.html");

        let orderURL = baseApiUrl + "/order";
        let headers = new Headers();

        headers.append("Authorization", getCookie(cookieName));
        const DOMElement = $("#orders-list");
        fetch(orderURL, {
                headers: headers
            })
            .then(r => r.json())
            .then(data => {
                data.forEach(e => {
                    DOMElement.append(insertli(insertdiv(e.customer_id + "<hr>" + e.product_id)));
                });
            })
            .catch(err => console.log(err))
    }

    function login(body) {
        const userLogin = baseApiUrl + "/login";

        fetch(userLogin, {
                method: "post",
                body: JSON.stringify(body),
                headers: {
                    "Access-Contol-Allow-Origin": "*",
                    "Content-type": "application/json"
                }
            })
            .then(r => r.json())
            .then((data) => {
                if (data.status === "Error") {
                    Object.keys(data.message).forEach(k => {
                        console.log(data.message[k][0]);
                    });
                } else {
                    setCookie(cookieName, data.data.token_type + " " + data.data.access_token, data.data.expires_at)
                    loadOrders()
                }
            })
            .catch(r => console.log(r));
    }

    function register(body) {
        const userRegister = baseApiUrl + "/register"

        fetch(userRegister, {
                method: "post",
                body: JSON.stringify(body),
                headers: {
                    "Access-Contol-Allow-Origin": "*",
                    "Content-type": "application/json"
                }
            })
            .then(data => {
                console.log(data)
                if (data.status === "Error") {
                    // do smg
                } else {
                    // do smg else
                    login({ email: body.email, password: body.password });
                }
            })
            .catch(err => console.log(err));
    }

    function setBtnOnClick(id, action) {
        $(id).click(action);
    }

    return {
        load: {
            loginForm: loadLoginForm,
            orders: loadOrders
        },
        auth: {
            login: login,
            register: register
        },
        setEvent: {
            onClick: setBtnOnClick
        }
    }
}