<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by TEMPLATED
http://templated.co
Released for free under the Creative Commons Attribution License

Name       : Concerted 
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20131014

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />

    <link rel="stylesheet" href="https://bulma.io/css/bulma-docs.min.css?v=202012211605">

    <link rel="canonical" href="https://bulma.io/documentation/components/modal/">
    <link rel="alternate" type="application/rss+xml" title="Bulma: Free, open source, and modern CSS framework based on Flexbox" href="https://bulma.io/atom.xml">

<!--[if IE 6]><link href="default_ie6.css" rel="stylesheet" type="text/css" /><![endif]-->

</head>
<body>
<div id="header" class="container">
	<div id="logo">
		<h1>Test Modal</h1>
	</div>
	<div style="margin-top: 15px;">
		<a href="#" accesskey="0" title="" id="doModal">Do modal</a>
	</div>
</div>
<div style="text-align: center">
	AAA BBB CCC
</div>

<div id="modal1" class="modal">
    <div class="modal-background"></div>
	<div class="modal-content has-background-white py-5 px-5">
        <h3 class="title mb-6">Modal Title</h3>
        <form class="">
            <div class="field">
                <label class="label">Name</label>
                <div class="control">
                    <input type="text" class="input" placeholder="Name">
                </div>
            </div>
            <div class="field">
                <label class="label">Email</label>
                <div class="control">
                    <input type="text" class="input" placeholder="Email">
                </div>
            </div>
            <div class="field">
                <div class="control">
                    <div class="select is-dark">
                        <select id="" name="">
                            <option>Small (100g)</option>
                            <option>Medium (150g)</option>
                            <option>Large (200g)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="field">
                <div class="control">
                    <label class="checkbox">
                        <!-- NB input tag is inside the label for checkbox -->
                        <input type="checkbox" class="">
                        I agree to the <a href="">terms and conditions</a>
                    </label>
                </div>
            </div>
            <div class="mt-6 has-text-right">
                <button class="button is-success" aria-label="submit">Submit</button>
                <button id="modal1Cancel" class="button is-cancel" aria-label="cancel">Cancel</button>
                <button class="button is-warning"  id="doModal2" aria-label="do modal 2">Do Modal 2</button>
            </div>
        </form>
	</div>
    <button id="modal1Close" class="modal-close is-large" aria-label="close"></button>
</div>

<div id="modal2" class="modal">
    <div class="modal-background"></div>
    <div class="modal-content has-background-white py-5 px-5">
        <h3 class="title mb-6">Modal Title 2</h3>
        <button id="modal2Cancel" class="button is-cancel" aria-label="cancel">Cancel</button>
    </div>
    <button id="modal2Close" class="modal-close is-large" aria-label="close"></button>
</div>

<script type="text/javascript">

        const doModalButton = document.querySelector('#doModal');
        const doModal2Button = document.querySelector('#doModal2');
        const modal1Cancel = document.querySelector('#modal1Cancel');
        const modal2Cancel = document.querySelector('#modal2Cancel');
        const modal1Close = document.querySelector('#modal1Close');
        const modal2Close = document.querySelector('#modal2Close');

        const modal1 = document.querySelector('#modal1');
        const modal2 = document.querySelector('#modal2');

        function doModal2Close() {
            modal2.classList.remove('is-active');

            console.log('Refresh modal 1');
        }

        doModalButton.addEventListener('click', () => {
            modal1.classList.add('is-active');
        });
        doModal2Button.addEventListener('click', () => {
            modal2.classList.add('is-active');
        });
        modal1Cancel.addEventListener('click', () => {
            modal1.classList.remove('is-active');
        });
        modal2Cancel.addEventListener('click', () => {
            doModal2Close();
        });
        modal1Close.addEventListener('click', () => {
            modal1.classList.remove('is-active');
        });
        modal2Close.addEventListener('click', () => {
            doModal2Close();
        });

</script>

</body>
</html>
