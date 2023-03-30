class VideoRecorder {
    static get toolbox() {
        return {
            title: 'Video Recorder',
            icon: 'VID'
        };
    }

    constructor({ data, config, api }) {
        console.log('data', data);
        console.log('config', config);
        console.log('api', api);

        super({ data, config, api });
        this.data.meta.type = data && data.meta ? data.meta: {};
    }

    /**
     * Create the video controls
     */
    renderActions() {
        const videoControls = document.createElement('div');
        videoControls.classList.add('w-full');
        videoControls.classList.add('p-4');
        videoControls.classList.add('flex');
        videoControls.classList.add('flex-row');
        videoControls.classList.add('justify-between');
    }
}

/*************************************************/
// Below is an example from overlay web to create custom editor js blocks

import LinkTool from '@editorjs/link';

class CustomLinkTool extends LinkTool {
    static get toolbox() {
      return {
        title: 'Custom Action',
        icon: 'HI'
      };
    }

    constructor({data, config, api}) {
      console.log(data);
      super({data, config, api});
      this.data.meta.type = data && data.meta ? data.meta : {};
    }

    /**
     * Return a list of buttons for the LinkTool, including the additional radio buttons for meta values.
     */
    renderActions() {
        const metaButtons = document.createElement('div');
        metaButtons.classList.add('cdx-custom-link__meta-buttons');

        const metaLabel = document.createElement('span');
        metaLabel.classList.add('cdx-custom-link__meta-label');
        metaLabel.innerText = 'Type: ';
        metaButtons.appendChild(metaLabel);

        const metaValues = [
          { label: 'External URL', value: 'externalUrl' },
          { label: 'Internal Deep Link', value: 'internalDeeplink' },
          { label: 'External Deep Link', value: 'externalDeeplink' },
          { label: 'API Request', value: 'serverCall' },
        ];

        metaValues.forEach((metaValue) => {
          const metaLabelItem = document.createElement('label');
          metaLabelItem.classList.add('cdx-custom-link__meta-label-item');

          const metaRadio = document.createElement('input');
          metaRadio.type = 'radio';
          metaRadio.name = 'metaType';
          metaRadio.value = metaValue.value;
          metaRadio.classList.add('cdx-custom-link__meta-radio');

          metaRadio.addEventListener('click', (event) => {
            this.data.buttonType = event.target.value;
            this.data.meta.type = event.target.value;
          });

          metaLabelItem.appendChild(metaRadio);

          const metaLabelText = document.createTextNode(metaValue.label);
          metaLabelItem.appendChild(metaLabelText);
          metaButtons.appendChild(metaLabelItem);
        });

        return metaButtons;
    }

    render() {
        //*******************************************//
        //************* Parent Element **************//
        //*******************************************//
        this.wrapper = document.createElement('div'); // Create the parent wrapper div
        this.wrapper.classList.add('cdx-custom-link'); // Add the unique classname

        //*******************************************//
        //************* Action Element **************//
        //*******************************************//
        const actionLabel = document.createElement('label'); // Create the action label
        actionLabel.classList.add('cdx-custom-link__action-label'); // Add the class to the action label
        actionLabel.innerText = 'Action'; // Adding the text to the action label

        console.log(this);
        const link = document.createElement('input');
        link.classList.add('link-tool__input');
        link.classList.add('cdx-input');
        link.type = 'text';
        link.name = 'actionValue';
        link.innerText = this.data.link;

        this.wrapper.appendChild(actionLabel);
        this.wrapper.appendChild(link);

        //*******************************************//
        //************* Button Element **************//
        //*******************************************//
        const buttonText = document.createElement('label');
        buttonText.classList.add('cdx-custom-link__button-text');
        buttonText.innerText = 'Button text';

        const buttonLabel = document.createElement('input');
        buttonLabel.classList.add('link-tool__input');
        buttonLabel.classList.add('cdx-input');
        buttonLabel.type = 'text';
        buttonLabel.name = 'buttonLabel';
        buttonLabel.innerText = this.data.buttonLabel;

        this.wrapper.appendChild(buttonText);
        this.wrapper.appendChild(buttonLabel);

        //*******************************************//
        //************ Action Selectors *************//
        //*******************************************//
        const actions = document.createElement('div');
        actions.classList.add('cdx-custom-link__actions');
        const metaButtons = this.renderActions();
        actions.appendChild(metaButtons);
        this.wrapper.appendChild(actions);

        return this.wrapper
    }

    /**
     * Override the default save function to include the meta values.
     */
    save() {
      console.log('the Data should be displayed here', this.wrapper)
      const savedData = super.save();
      // console.log(this.data.meta.type);
      savedData.buttonType = this.data.meta.type;
      savedData.link = this.wrapper.querySelector('input[name="actionValue"]').value;
      savedData.buttonAction = this.wrapper.querySelector('input[name="actionValue"]').value
      savedData.buttonText = this.wrapper.querySelector('input[name="buttonLabel"').value;
      return savedData;
    }
}

export default CustomLinkTool;

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
