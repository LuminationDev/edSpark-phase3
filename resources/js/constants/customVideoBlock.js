class VideoRecorder {
    static get toolbox() {
        return {
            title: 'Video Recorder',
            icon: '<?xml version="1.0" encoding="iso-8859-1"?><!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  --><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 332.804 332.804" style="enable-background:new 0 0 332.804 332.804;" xml:space="preserve"><g><g><g><path d="M330.804,171.002c-3.6-6.4-12-8.8-18.8-4.8l-45.6,26.4l-11.6,6.8v63.2l10.8,6.4c0.4,0,0.4,0.4,0.8,0.4l44.8,26c2,1.6,4.8,2.4,7.6,2.4c7.6,0,13.6-6,13.6-13.6v-53.6l0.4-52.8C332.804,175.402,332.404,173.002,330.804,171.002z"/><path d="M64.404,150.602c35.6,0,64.4-28.8,64.4-64.4c0-35.6-28.8-64.4-64.4-64.4s-64.4,28.8-64.4,64.4C-0.396,121.802,28.804,150.602,64.404,150.602z M64.404,59.802c14.8,0,26.4,12,26.4,26.4c0,14.8-12,26.4-26.4,26.4c-14.4,0-26.4-12-26.4-26.4C37.604,71.402,49.604,59.802,64.404,59.802z"/><path d="M227.604,154.202c-10.4,5.2-22,8.4-34.4,8.4c-15.2,0-29.6-4.4-41.6-12.4h-45.6c-12,8-26.4,12.4-41.6,12.4c-12.4,0-24-2.8-34.4-8.4c-9.2,5.2-15.6,15.6-15.6,26.8v97.6c0,18,14.8,32.4,32.4,32.4h164.4c18,0,32.4-14.8,32.4-32.4v-97.6C243.204,169.802,236.804,159.402,227.604,154.202z"/><path d="M193.204,150.602c35.6,0,64.4-28.8,64.4-64.4c0-35.6-28.8-64.4-64.4-64.4c-35.6,0-64.4,28.8-64.4,64.4C128.804,121.802,157.604,150.602,193.204,150.602z M193.204,59.802c14.8,0,26.4,12,26.4,26.4c0,14.8-12,26.4-26.4,26.4c-14.4,0-26.4-12-26.4-26.4C166.804,71.402,178.404,59.802,193.204,59.802z"/></g></g></g></svg>',
        }
    }

    constructor({ data, api }) {
        this.api = api;

        this.recButton = null;
        //  document.getElementById('recordButton');
    }

    renderActions() {
        // Create record button
        const recordButton = document.createElement('div');

        // Add record button classes
        recordButton.classList.add('absolute');
        recordButton.classList.add('top-1/2');
        recordButton.classList.add('left-1/2');
        recordButton.classList.add('-translate-x-1/2');
        recordButton.classList.add('-translate-y-1/2');
        recordButton.classList.add('w-fit');
        recordButton.classList.add('h-fit');

        // Create the record inner button
        this.recButton = document.createElement('button');
        // const record = document.createElement('button');
        this.recButton.classList.add('rounded-full');
        this.recButton.classList.add('w-[96px]');
        this.recButton.classList.add('h-[96px]');
        this.recButton.classList.add('bg-[#EF5350]');
        this.recButton.classList.add('flex');
        this.recButton.classList.add('place-items-center');
        this.recButton.classList.add('justify-center');
        this.recButton.classList.add('hover:bg-[#ab3b39]');
        // Add record button inner id
        this.recButton.setAttribute('id', 'recordButton');

        // Create inner 'REC' text
        const rec = document.createElement('span');
        rec.classList.add('text-white');
        rec.classList.add('font-bold');
        rec.classList.add('text-[33px]');
        rec.innerText = 'REC';


        // Create Stop button
        const stopButton = document.createElement('button');
        const stopIcon = document.createElement('img');
        stopIcon.src = '/storage/uploads/videoControls/stop-white.png';
        // stopButton.classList.add('hidden');
        stopButton.setAttribute('id', 'stopButton');
        stopButton.appendChild(stopIcon);


        // Create play-back button
        const playbackButton = document.createElement('button');
        const buttonIcon = document.createElement('img');
        buttonIcon.src = '/storage/uploads/videoControls/play-button-arrowhead-white.png';
        playbackButton.setAttribute('id', 'playButton');
        playbackButton.appendChild(buttonIcon);


        // Create pause button
        const pauseButton = document.createElement('button');
        const pauseIcon = document.createElement('img');
        pauseIcon.src = '/storage/uploads/videoControls/pause-white.png';
        pauseButton.setAttribute('id', 'pauseButton');
        pauseButton.appendChild(pauseIcon);
        // Add pause button classes


        // Create playback progress bar
        const progressBar = document.createElement('div');
        progressBar.classList.add('w-[450px]');
        progressBar.classList.add('h-[6px]');
        progressBar.classList.add('rounded');
        progressBar.classList.add('bg-[#B83F3D]');

        // Add progress bar id
        progressBar.setAttribute('id', 'progressBar');
        // Add progress bar classes


        // Create reset
        const resetPlayback = document.createElement('div');
        const resetIcon = document.createElement('img');
        resetIcon.src = '/storage/uploads/videoControls/undo-circular-arrow-white.png';
        resetPlayback.setAttribute('id', 'resetPlyaback');
        resetPlayback.appendChild(resetIcon);


        const countdown = document.createElement('div');
        countdown.setAttribute('id', 'countdown');
        countdown.classList.add('hidden');
        countdown.classList.add('absolute');
        countdown.classList.add('left-0');
        countdown.classList.add('top-0');
        countdown.classList.add('w-full');
        countdown.classList.add('h-full');

        // countdown.classList.add('-translate-x-1/2');
        // countdown.classList.add('-translate-y-1/2');



        // Create playback elapsed
        const timeElapsed = document.createElement('span');

        // Add playback elapsed id
        timeElapsed.setAttribute('id', 'timeElapsed');
        // Add playback elapsed classes

        // Create playback remaining
        const timeRemaining = document.createElement('span');

        // Add playback elapsed id
        timeRemaining.setAttribute('id', 'timeRemaining');
        // Add playback elapsed classes


        // Create re-record button
        const reRecordButton = document.createElement('button');

        // Add re-record button id
        reRecordButton.setAttribute('id', 'reRecordButton');
        // Add re-record button classes


        // Create parent elements for each


        // Append children to parents
        recordButton.appendChild(this.recButton);
        this.recButton.appendChild(rec);
        // this.recButton.appendChild(stopButton);

        return {
            recordButton,
            stopButton,
            playbackButton,
            pauseButton,
            progressBar,
            resetPlayback,
            countdown
        }

    }

    render() {
        // Set the container document
        const videoContainer = document.createElement('div');
        // Add the classes
        videoContainer.classList.add('w-full');
        // videoContainer.classList.add('p-4');
        videoContainer.classList.add('flex');
        videoContainer.classList.add('flex-row');
        videoContainer.classList.add('justify-between');

        // Set the inner container
        const videoContainerInner = document.createElement('div');
        // Add the classes
        videoContainerInner.classList.add('h-[365px]');
        videoContainerInner.classList.add('w-full');
        videoContainerInner.classList.add('relative');

        // Set the video player
        const videoPlayer = document.createElement('video');
        // Set the classes
        videoPlayer.classList.add('h-[365px]');
        videoPlayer.classList.add('w-full');
        videoPlayer.classList.add('object-cover');
        // videoPlayer.classList.add('relative');
        // Set the id
        videoPlayer.setAttribute('id', 'video_player');

        // Add a download link (for testing)
        const download = document.createElement('button');
        download.classList.add('px-6');
        download.classList.add('py-6');
        download.classList.add('bg-white');
        download.classList.add('text-black');
        download.download = 'test.webm';

        download.innerText = 'Download';

        // Create a container to float in the boc
        const controlContainer = document.createElement('div');
        controlContainer.classList.add('absolute');
        controlContainer.classList.add('bottom-0');
        controlContainer.classList.add('left-1/2');
        controlContainer.classList.add('-translate-x-1/2')
        controlContainer.classList.add('flex');
        controlContainer.classList.add('justify-between');
        controlContainer.classList.add('place-items-center');
        controlContainer.classList.add('gap-6');
        controlContainer.classList.add('py-4');
        controlContainer.classList.add('px-12');




        // Get the controls
        const controls = this.renderActions();

        // Append into the parent
        videoContainer.appendChild(videoContainerInner);
        videoContainerInner.appendChild(videoPlayer);
        videoContainerInner.appendChild(controls.recordButton);
        videoContainerInner.appendChild(controls.countdown);
        videoContainerInner.appendChild(controlContainer);




        controlContainer.appendChild(controls.playbackButton);
        controlContainer.appendChild(controls.pauseButton);
        controlContainer.appendChild(controls.progressBar);
        controlContainer.appendChild(controls.stopButton);
        controlContainer.appendChild(controls.resetPlayback);

        // controlContainer.appendChild(download);
        // videoPlayer.appendChild(controls);

        openCam();
        return videoContainer;
    }

    save(blockContent) {
        return {
            file: blockContent.value
        }
    }

    recButtonClick() {
        console.log(this.api)
        this.recButton.addEventListener('click', () => {
            console.log('click');
        })
        this.api.listeners.on(this.recButton, 'click', () => {
            console.log('Button clicked!');
        }, true);
    }
}

export default VideoRecorder;

/**
 * HELPER - get the camera and check for audio and video permissions
 */
const openCam = () => {
    // let allMediaDevices = navigator.mediaDevices;
    // if (!allMediaDevices || !allMediaDevices.getUserMedia) {
    //     console.log('Get user media not supported');
    //     return;
    // }

    // allMediaDevices.getUserMedia({
    //     audio: true,
    //     video: true
    // }).then(stream => {
    //     const video = document.getElementById('video_player');

    //     if ('srcObject' in video) {
    //         video.srcObject = stream;
    //     } else {
    //         video.src = window.URL.createObjectURL(stream);
    //     };

    //     video.onloadedmetadata = (e) => {
    //         video.play();
    //     };
    // }).catch(error => {
    //     console.log(error.name +': ' + error.message);
    // })
}


















/*************************************************/
// Below is an example from overlay web to create custom editor js blocks

// import LinkTool from '@editorjs/link';

// class CustomLinkTool extends LinkTool {
//     static get toolbox() {
//       return {
//         title: 'Custom Action',
//         icon: 'HI'
//       };
//     }

//     constructor({data, config, api}) {
//       console.log(data);
//       super({data, config, api});
//       this.data.meta.type = data && data.meta ? data.meta : {};
//     }

//     /**
//      * Return a list of buttons for the LinkTool, including the additional radio buttons for meta values.
//      */
//     renderActions() {
//         const metaButtons = document.createElement('div');
//         metaButtons.classList.add('cdx-custom-link__meta-buttons');

//         const metaLabel = document.createElement('span');
//         metaLabel.classList.add('cdx-custom-link__meta-label');
//         metaLabel.innerText = 'Type: ';
//         metaButtons.appendChild(metaLabel);

//         const metaValues = [
//           { label: 'External URL', value: 'externalUrl' },
//           { label: 'Internal Deep Link', value: 'internalDeeplink' },
//           { label: 'External Deep Link', value: 'externalDeeplink' },
//           { label: 'API Request', value: 'serverCall' },
//         ];

//         metaValues.forEach((metaValue) => {
//           const metaLabelItem = document.createElement('label');
//           metaLabelItem.classList.add('cdx-custom-link__meta-label-item');

//           const metaRadio = document.createElement('input');
//           metaRadio.type = 'radio';
//           metaRadio.name = 'metaType';
//           metaRadio.value = metaValue.value;
//           metaRadio.classList.add('cdx-custom-link__meta-radio');

//           metaRadio.addEventListener('click', (event) => {
//             this.data.buttonType = event.target.value;
//             this.data.meta.type = event.target.value;
//           });

//           metaLabelItem.appendChild(metaRadio);

//           const metaLabelText = document.createTextNode(metaValue.label);
//           metaLabelItem.appendChild(metaLabelText);
//           metaButtons.appendChild(metaLabelItem);
//         });

//         return metaButtons;
//     }

//     render() {
//         //*******************************************//
//         //************* Parent Element **************//
//         //*******************************************//
//         this.wrapper = document.createElement('div'); // Create the parent wrapper div
//         this.wrapper.classList.add('cdx-custom-link'); // Add the unique classname

//         //*******************************************//
//         //************* Action Element **************//
//         //*******************************************//
//         const actionLabel = document.createElement('label'); // Create the action label
//         actionLabel.classList.add('cdx-custom-link__action-label'); // Add the class to the action label
//         actionLabel.innerText = 'Action'; // Adding the text to the action label

//         console.log(this);
//         const link = document.createElement('input');
//         link.classList.add('link-tool__input');
//         link.classList.add('cdx-input');
//         link.type = 'text';
//         link.name = 'actionValue';
//         link.innerText = this.data.link;

//         this.wrapper.appendChild(actionLabel);
//         this.wrapper.appendChild(link);

//         //*******************************************//
//         //************* Button Element **************//
//         //*******************************************//
//         const buttonText = document.createElement('label');
//         buttonText.classList.add('cdx-custom-link__button-text');
//         buttonText.innerText = 'Button text';

//         const buttonLabel = document.createElement('input');
//         buttonLabel.classList.add('link-tool__input');
//         buttonLabel.classList.add('cdx-input');
//         buttonLabel.type = 'text';
//         buttonLabel.name = 'buttonLabel';
//         buttonLabel.innerText = this.data.buttonLabel;

//         this.wrapper.appendChild(buttonText);
//         this.wrapper.appendChild(buttonLabel);

//         //*******************************************//
//         //************ Action Selectors *************//
//         //*******************************************//
//         const actions = document.createElement('div');
//         actions.classList.add('cdx-custom-link__actions');
//         const metaButtons = this.renderActions();
//         actions.appendChild(metaButtons);
//         this.wrapper.appendChild(actions);

//         return this.wrapper
//     }

//     /**
//      * Override the default save function to include the meta values.
//      */
//     save() {
//       console.log('the Data should be displayed here', this.wrapper)
//       const savedData = super.save();
//       // console.log(this.data.meta.type);
//       savedData.buttonType = this.data.meta.type;
//       savedData.link = this.wrapper.querySelector('input[name="actionValue"]').value;
//       savedData.buttonAction = this.wrapper.querySelector('input[name="actionValue"]').value
//       savedData.buttonText = this.wrapper.querySelector('input[name="buttonLabel"').value;
//       return savedData;
//     }
// }

// export default CustomLinkTool;

/*****************************************************/
// The foloowing code is from the original futureSchools
// and is for recording video through webcame


// import QencodeApiClient from 'qencode-api';
// import * as tus from 'tus-js-client';
// import { getStorage, ref, uploadBytes } from 'firebase/storage';
// import { hasRecorded } from './recordCheck';



// // Create the function as an export to use in authoring.js
// export const startVideoRecording = () => {

//   /****************************************/
//   // Begin by clearing the progress interval
//   clearInterval(progressInterval);

//   /****************************************/
//   // Use an external function to pass the recorded state
//   hasRecorded(false);

//   /****************************************/
//   // Set re-record state to false by default
//   var reRecord = false;
//   var isPaused = false;
//   var restart = false;

//   /****************************************/
//   // Set the seconds count to 0 by default
//   var seconds=0;

//   /****************************************/
//   // Get all of the elements required for the recorder
//   var player=  $('#video_player');
//   var playback = $('#video_playback');
//   var stopButton = $('#webcam-done');
//   var pauseButton = $('#webcam-pause');
//   var playButton = $('#webcam-play');
//   var recButton = $('#webcam-rec-btn')
//   var redoButton = $('#webcam-redo');
//   var controlsBar = $('.controls-bar');
//   var progBar = $('.webcam_prog'); // This has been changed slightly - TODO(set the new prog bar as a variable)
//   var submitVideoBtn = $('#submitVideoBtn');
//   var reSubmit = $('#reSubmitVideoBtn');

//   /****************************************/
//   // Setting the video mime-type to h264 codec
//   const options = {mimeType: 'video/webm;codecs:h264'};

//   /****************************************/
//   // Set the video recorder buttons to default
//   recButton.show();
//   recButton.css({
//     'pointer-events': 'revert',
//     'pointer': 'cursor',
//   });
//   recButton.find('svg').css({
//     'fill': '#e2203a',
//   });
//   $('.countdown_container').css({
//     'background': 'rgba(0,0,0,0.7)',
//     'backdrop-filter': 'blur(5px)',
//   });
//   controlsBar.hide();
//   playback.hide();
//   player.show();
//   redoButton.hide();

//   /****************************************/
//   // Function that checks states and runs on video recording completed
//   var handleSuccess = function(stream) {
//       var recordedChunks = [];
//       var mediaRecorder = new MediaRecorder(stream, options);

//       player.get(0).srcObject = stream;
//       player.get(0).play();
//       console.log('welcome to the video recorder');

//       // const reRecordVideo = () => {
//       //   // reRecord = true;
//       //   // recordedChunks = [];
//       //   // seconds=0;
//       //   // progBar.attr('value',seconds);
//       //   // restart = true;
//       //   // clearInterval(progressInterval);
//       //   console.log(reRecord);
//       // }

//       function redo() {
//           $('.countdown_container').css({
//             'background': 'rgba(0,0,0,0.7)',
//             'backdrop-filter': 'blur(5px)',
//           });
//           if (mediaRecorder.state === 'inactive') {
//             mediaRecorder.start();
//           }
//           console.log(mediaRecorder.state);
//           reRecord = true;
//           recordedChunks=[];
//           seconds=0;
//           progBar.attr('value',seconds);
//           restart=true;
//           // mediaRecorder.stop();
//           clearInterval(progressInterval);
//       }

//       function onStop(e) {
//           if (restart) {
//               restart=false;
//               recordedChunks=[];
//               mediaRecorder = new MediaRecorder(stream, options);
//               mediaRecorder.addEventListener('dataavailable',dataAvailable);
//               mediaRecorder.addEventListener('stop',onStop);
//               clearInterval(progressInterval);
//               console.log('HECKING DAMN')
//               mediaRecorder.start(1000);
//           } else {
//               clearInterval(progressInterval);
//               isPaused = true;
//               hasRecorded(true);
//               $('#spinner').show();
//               submitVideoBtn.hide();
//               reSubmit.hide();
//               // playback.show();
//               // playback.attr('src', URL.createObjectURL(new Blob(recordedChunks, {type: 'video/webm;codecs:h264'})));
//               // playback.get(0).play();
//               // player.hide();
//               //code to send to qencode server
//               e.preventDefault();
//               const apiKey = '624d31e1b4204';
//               const qencodeApiClient = new QencodeApiClient(apiKey)
//               let task = qencodeApiClient.CreateTask();
//               var file = new Blob(recordedChunks, {type: 'video/webm;codecs:h264'});
//               var upload = new tus.Upload(file, {
//                   endpoint: task.uploadUrl + '/' + task.taskToken,
//                   // endpoint: 'https://tusd.tusdemo.net/files/',
//                   retryDelays: [0, 3000, 5000, 10000, 20000],
//                   metadata: {
//                       filename: 'recording.webm',
//                       filetype: file.type,
//                   },
//                   onError: function (error) {
//                       console.log('Failed because: ' + error)
//                   },
//                   onProgress: function (bytesUploaded, bytesTotal) {
//                       var percentage = (bytesUploaded / bytesTotal * 100).toFixed(2)
//                       console.log(bytesUploaded, bytesTotal, percentage + '%')

//                   },
//                   onSuccess: function () {
//                       $('#spinner').hide();
//                       console.log('Download %s from %s uid: %s', upload.file.name, upload.url, upload);
//                       const uuid = upload.url.split('/')[6];
//                       console.log(uuid);
//                       let query = {
//                           'source': 'tus:' + uuid,
//                           'format': [
//                               {
//                                   'output': 'mp4',
//                                   'size': '320x240',
//                                   'video_codec': 'libx264',
//                               },
//                           ],
//                       };
//                       task.StartCustom(query);

//                       const storage = getStorage();
//                       const storageRef = ref(storage, uuid);
//                       // console.log(uuid);
//                       const metadata = {
//                         customMetadata: {
//                           'userName': $('#post_author_name').val(),
//                           'userId': $('#user_author_id').val(),
//                         },
//                       };

//                       // console.log(metadata);

//                       try {
//                         uploadBytes(storageRef, file, metadata).then((snapshot) => {
//                           console.log(snapshot);
//                           console.log('Upload of blob or file to firebase cloud succesful');
//                           $('#video_playback').append(`<input id="video_uid" type="hidden" value="${uuid}" name="video_uuid">`);
//                           $('.success_tick').show();
//                         });
//                       } catch(e) {
//                         console.log('Error uploading file: ' + e);
//                       }

//                   },
//               })
//               // Check if there are any previous uploads to continue.
//               upload.findPreviousUploads().then(function (previousUploads) {
//                   // Found previous uploads so we select the first one.
//                   if (previousUploads.length) {
//                       upload.resumeFromPreviousUpload(previousUploads[0])
//                   }
//                   // Start the upload
//                   upload.start()
//               });
//           }

//       }

//       function onStopClick() {
//           isPaused = true;
//           clearInterval(progressInterval);
//           reSubmit.css({display: 'flex'});
//           console.log(mediaRecorder);
//           if (mediaRecorder.state !== 'inactive') {
//             mediaRecorder.stop();
//           } else if (playback.get(0).play() == true) {
//             console.log('still playing');
//             playback.get(0).pause();
//           }
//           $('.controls-bar').hide();
//           submitVideoBtn.attr('disabled', false);
//           playback.show();
//           playback.attr('src', URL.createObjectURL(new Blob(recordedChunks, {type: 'video/webm;codecs:h264'})));

//           var vidd = document.getElementById('video_playback');
//           vidd.addEventListener('loadedmetadata', function() {
//             if (vidd.duration === Infinity) {
//               vidd.currentTime = 1e101;
//               vidd.ontimeupdate = function() {
//                 this.ontimeupdate = () => {
//                   return;
//                 }
//                 vidd.currentTime = 0;
//                 console.log(vidd.duration);
//                 return;
//               }

//             }
//           });

//           playback.get(0).play();
//           player.hide();
//       }

//       function pauseClick() {
//           isPaused = true;
//           pauseButton.attr('class','hidden');
//           playButton.removeAttr('class');
//           if (mediaRecorder.state !== 'inactive') {
//             mediaRecorder.pause();
//           }
//           // mediaRecorder.pause();
//           player.get(0).pause();
//       }

//       function playClick() {
//           isPaused = false;
//           playButton.attr('class','hidden');
//           pauseButton.removeAttr('class');
//           if (mediaRecorder.state !== 'inactive') {
//             mediaRecorder.resume();
//           }
//           player.get(0).play();
//       }

//       function recClick() {
//         countDown();
//         // console.log('cllllick');
//         // playback.hide();
//         // playback.attr('src', '');
//         seconds = 0;
//         if (mediaRecorder.state == 'recording') {
//           mediaRecorder.stop();
//         }
//         console.log(mediaRecorder.state);


//       }

//       function dataAvailable(e) {
//           if (reRecord == true) {
//             seconds = 0
//           }

//           if (e.data.size > 0) {
//               recordedChunks.push(e.data);
//           }
//       }

//       // function reStartRecording() {
//       //   restart = true;
//       //   recClick();
//       // }

//       reSubmit.click(startVideoRecording);
//       // redoButton.click(startVideoRecording);
//       redoButton.click(redo);
//       stopButton.click(() => {
//         onStopClick(isPaused, progressInterval, mediaRecorder, playback, recordedChunks)
//       });
//       pauseButton.click(pauseClick);
//       playButton.click(playClick);
//       recButton.click(recClick);
//       mediaRecorder.addEventListener('dataavailable',dataAvailable);
//       submitVideoBtn.on('click', onStop);
//       // mediaRecorder.addEventListener('stop',onStop);

//       const countDown = () => {

//         // disable the record button to avoid "features"
//         recButton.css({
//           'pointer-events': 'none',
//           'pointer': 'none',
//         });
//         recButton.find('svg').css({
//           'fill': '#8b1323',
//         });
//         // set the timeout length
//         var timeleft = 4;
//         // start a countdown to run the length of above
//         // length
//         var downloadTimer = setInterval(function(){
//           // wrap record start, button states and clearInterval in a
//           // check to see if timer is over
//           if(timeleft <= 1){
//             clearInterval(downloadTimer); // clear interal
//             mediaRecorder.start(1000); // start recorder
//             setInterval(progressInterval, 10); // starting progress bar interval (show video record length)
//             $('.countdown_container').css({
//               'background': 'transparent',
//               'backdrop-filter': 'unset',
//             });
//             recButton.hide(); // hide record button
//             controlsBar.show(); // show video recording controls
//           }
//           // remove 1 second from timeout length
//           timeleft -= 1;
//           // set the countdown indicator
//           $('.count_display').text(function() {
//             // console.log($(this).text());
//             if (timeleft > 0) {
//               return timeleft;
//             } else {
//               return '';
//             }
//           });
//         }, 1000);
//         seconds = 0;
//       }

//       // var progressInterval = () => {
//       //   if (isPaused === false) {
//       //     // seconds = 0;
//       //     seconds += 0.01;
//       //     // progBar.attr('value', seconds);
//       //     let percentComplete = (seconds/60) * 100;
//       //     $('.webcam_prog').css({'width': percentComplete + '%'});
//       //     let timer = Math.round(seconds);
//       //     let startMins = parseInt('00');
//       //     let startSecs = parseInt('00');
//       //     let counter = startSecs += timer;
//       //     console.log(startSecs);
//       //     console.log(timer);
//       //     console.log(counter);
//       //     $('.progress_counter').text(`${(startMins < 10) ? '0' + startMins : startMins}:${(counter < 10) ? '0' + counter : counter}`);
//       //     if (percentComplete >= 100) {
//       //       onStopClick();
//       //     }
//       //   }

//       // }
//   }

//    // set interval for recording progress bar
//   // TODO: add in countdown text alongside progress bar
//   var progressInterval = () => {
//     if (isPaused === false) {
//       // seconds = 0;
//       seconds += 0.01;
//       // progBar.attr('value', seconds);
//       let percentComplete = (seconds/60) * 100;
//       $('.webcam_prog').css({'width': percentComplete + '%'});
//       let timer = Math.round(seconds);
//       let startMins = parseInt('00');
//       let startSecs = parseInt('00');
//       let counter = startSecs += timer;
//       console.log(startSecs);
//       console.log(timer);
//       console.log(counter);
//       $('.progress_counter').text(`${(startMins < 10) ? '0' + startMins : startMins}:${(counter < 10) ? '0' + counter : counter}`);
//       if (percentComplete >= 100) {
//         // onStopClick(isPaused, progressInterval, mediaRecorder, playback);
//       }
//     }

//   }


//   const checkMedia = () => {
//     if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
//       navigator.mediaDevices.getUserMedia({audio: true, video: true})
//           .then(handleSuccess)
//           .catch(function (err) {
//               /* handle the error */
//               console.log(err);
//               window.alert('Your webcam is already in use. Please close the application using the camera and re-load the page!');
//               $('#webcam-rec-btn').on('click', () => {
//                 window.alert('Your webcam is already in use. Please close the application using the camera and re-load the page!');
//               });

//               $('.video_row').css({width: '100%', height: '300px', background: '#000', position: 'relative'});
//               $('.video_row').find('.col-12').append('<h2 class="camera_warning_msg">Sorry, your camera is being used elsewhere.<br>Please quit all other applications and reload this page.</h2>');
//               $('.camera_warning_msg').css({color: '#fff', position: 'absolute', fontSize: '1.125rem', top: '50%', left: '50%', transform: 'translate(-50%, -50%)'});
//           });
//     }
//   }

//   checkMedia();

//   var icon = $('.play');
//   icon.click(function () {
//       icon.toggleClass('active');
//       return false;
//   });

//   // reSubmit.click(checkMedia);
// }
