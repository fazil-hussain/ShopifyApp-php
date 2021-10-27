</main>
<script src="https://unpkg.com/@shopify/app-bridge@2"></script>
<script src="https://unpkg.com/@shopify/app-bridge-utils"></script>
<script src="https://unpkg.com/axios/dist/axios.js"></script>
<script>

    /*
============================================
    Initializing variable for AppBridge
============================================
*/
    var AppBridge = window['app-bridge'];
    var AppBridgeUtil = window['app-bridge-utils'];
    var actions = window['app-bridge'].actions;
    var createApp = AppBridge.default;
    var TitleBar = actions.TitleBar;
    var Button = actions.Button;
    var Redirect = actions.Redirect;
    var Modal = actions.Modal;

    /*
============================================
    Creating AppBridge
============================================
*/
    var app = createApp({
        apiKey: 'c086c1ff8ecbd235dd6aa7cc54b77f65',
        host: 'https://7831-2400-adc7-3101-5c00-9c41-da19-63a6-6e4.ngrok.io',
        shopOrigin: '<?php echo $shopify->get_url(); ?>',
    });

    /*
============================================
    Creating Button & Title 
============================================
*/
    var instalscriptBtn = Button.create(app, {
        label: 'install Script'
    });
    const titleBarOpt = {
        title: 'app',
        buttons: {
            primary: instalscriptBtn
        }
    }
/*
============================================
    Model Code
============================================
*/
    const modalOpt = {
        title: 'Example Title',
        message: 'Example Message',
    }
    // const redirect = Redirect.create(app);
    const exampleModal = Modal.create(app, modalOpt);
    instalscriptBtn.subscribe(Button.Action.CLICK, data => {
        // redirect.dispatch(Redirect.Action.APP, '/shopiapp/script-tags.php');

        exampleModal.dispatch(Modal.Action.OPEN);


    });
    const appTitlebar = TitleBar.create(app, titleBarOpt);

    //===========================================
    // Getting Session token 
    //===========================================

    const getSessionToken = AppBridgeUtil.getSessionToken;

    getSessionToken(app).then(token => {

        // console.log(token);
        var formData = new FormData();
        formData.append('token', token);
        fetch('verify_session.php', {
                method: 'POST',
                header: {
                    'Content-Type': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);

                if (data.success) {
                    axios({
                        method: 'POST',
                        url: 'authentecatedFetch.php',
                        data: {
                            shop: data.shop.host,
                            query: `query {
                                products(first:2){
                                edges{
                                node{
                                    id
                                    title
                                    vendor
                                    status
                                    images(first: 1) {
                                    edges {
                                        node {
                                        originalSrc
                                        }
                                    }
                                    }
                                }
                                }
                            }
                    }`
                        },
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer' + token
                        }
                    }).then(response => {
                        // console.log(response.data);
                    });
                }
            });

    });
</script>
</body>

</html>