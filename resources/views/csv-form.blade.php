<style>
    #main_frame {
        border: 5px solid red;
        padding: 25px;
        max-width: 850px;
        margin: 0 auto;
    }

    .main_content {
        text-align: center;
        margin: 50px;
    }
</style>

<h1 class="main_content">Laravel Server Send Event Assignment</h1>

<div id="main_frame">
    <div> EMAIL: <span id="content_email"></span></div>
    <div> PERSON PREFIX: <span id="content_prefix"></span></div>
    <div> FIRST NAME: <span id="content_first_name"></span></div>
    <div> LAST NAME: <span id="content_last_name"></span></div>
    <div> ACTIVE: <span id="content_active"></span></div>
    <h3 id="request_type"></h3>
    <h3 id="rawData"></h3>
</div>


<script>

    let evtSource = new EventSource("/getEventStreamServerSentEvent", {withCredentials: true});
    let oneTimeConfirmation = false;

    evtSource.onmessage = function (e) {

        let serverData = JSON.parse(e.data);

        let rawData = document.getElementById('rawData');
        let main_frame = document.getElementById('main_frame');
        let content_email = document.getElementById('content_email');
        let content_prefix = document.getElementById('content_prefix');
        let content_first_name = document.getElementById('content_first_name');
        let content_last_name = document.getElementById('content_last_name');
        let content_active = document.getElementById('content_active');

        let request_type = document.getElementById('request_type');

        content_email.textContent = serverData.email;
        content_prefix.textContent = serverData.prefix;
        content_first_name.textContent = serverData.first;
        content_last_name.textContent = serverData.last;
        content_active.textContent = serverData.active;

        request_type.textContent = (serverData.error === 0) ? 'Validating pass' : 'Validating fail';

        let color = getRandomColor();

        main_frame.style.borderColor = color;

        rawData.textContent = JSON.stringify(serverData);
        if (serverData.error && !oneTimeConfirmation) {
            let errorFound = confirm("This record contain error fix CSV now?");
            if (errorFound == true) {
                evtSource.close();
            } else {
                let checkOnlyValidRecordProcessing = confirm("Do you want to execute processing for valid records only?");
                if (checkOnlyValidRecordProcessing) {
                    oneTimeConfirmation = true;
                } else {
                    evtSource.close();
                    alert('Please fix CSV record');
                }
            }
        }
    };

    function getRandomColor() {
        let letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

</script>
