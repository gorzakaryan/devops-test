import './bootstrap';

window.Echo.channel('chat')
    .listen('MessageSent', (e) => {

        const div = document.getElementById('messages');
        // const now = new Date();

        div.innerHTML += `
            <p>${e.message}</p>
        `;

        console.log(e);
    });


const statusElement = document.getElementById('status');
window.Echo.channel('video-processing')

    .listen('VideoUploading', (e) => {

        console.log(e);

        statusElement.innerText = e.status;
    })
    .listen('VideoEncoding', (e) => {

        console.log(e);

        statusElement.innerText = e.status;
    })
    .listen('VideoCompleted', (e) => {

        console.log(e);

        statusElement.innerText = e.status;
    });
