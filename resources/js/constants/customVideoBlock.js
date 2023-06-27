class VideoRecorder {
    static get toolbox() {
        return {
            title: 'Video Recorder',
            icon: '<?xml version="1.0" encoding="iso-8859-1"?><!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  --><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 332.804 332.804" style="enable-background:new 0 0 332.804 332.804;" xml:space="preserve"><g><g><g><path d="M330.804,171.002c-3.6-6.4-12-8.8-18.8-4.8l-45.6,26.4l-11.6,6.8v63.2l10.8,6.4c0.4,0,0.4,0.4,0.8,0.4l44.8,26c2,1.6,4.8,2.4,7.6,2.4c7.6,0,13.6-6,13.6-13.6v-53.6l0.4-52.8C332.804,175.402,332.404,173.002,330.804,171.002z"/><path d="M64.404,150.602c35.6,0,64.4-28.8,64.4-64.4c0-35.6-28.8-64.4-64.4-64.4s-64.4,28.8-64.4,64.4C-0.396,121.802,28.804,150.602,64.404,150.602z M64.404,59.802c14.8,0,26.4,12,26.4,26.4c0,14.8-12,26.4-26.4,26.4c-14.4,0-26.4-12-26.4-26.4C37.604,71.402,49.604,59.802,64.404,59.802z"/><path d="M227.604,154.202c-10.4,5.2-22,8.4-34.4,8.4c-15.2,0-29.6-4.4-41.6-12.4h-45.6c-12,8-26.4,12.4-41.6,12.4c-12.4,0-24-2.8-34.4-8.4c-9.2,5.2-15.6,15.6-15.6,26.8v97.6c0,18,14.8,32.4,32.4,32.4h164.4c18,0,32.4-14.8,32.4-32.4v-97.6C243.204,169.802,236.804,159.402,227.604,154.202z"/><path d="M193.204,150.602c35.6,0,64.4-28.8,64.4-64.4c0-35.6-28.8-64.4-64.4-64.4c-35.6,0-64.4,28.8-64.4,64.4C128.804,121.802,157.604,150.602,193.204,150.602z M193.204,59.802c14.8,0,26.4,12,26.4,26.4c0,14.8-12,26.4-26.4,26.4c-14.4,0-26.4-12-26.4-26.4C166.804,71.402,178.404,59.802,193.204,59.802z"/></g></g></g></svg>',
        }
    }

    constructor({ data, api }) {
        console.log(data);
        this.api = api;
        this.data = data;

        this.recording = false;
        this.recButton = null;
        this.wrapper = undefined;

        this.mediaRecorder = null;
        this.stream = null;
        this.videoLocal = null;
        this.blobsRecorded = [];

        this.recordingLengthMS = 60000;
        /**
         * SET ALL THE NECESSARY ELEMENTS
         * include: wrapper elements for video players
         */

        // Video player (recording)
        this.videoRecorder = null;
        // Video player (recording) - wrapper
        this.videoRecorderWrapper = null;
        // Video player (playback)
        this.videoPlayback = null;
        // Video player (playback) - wrapper
        this.videoPlaybackWrapper = null;
        // Record button
        this.recordButton = null;
        // Countdown
        this.countdown = null;
        // Play
        this.playButton = null;
        // Pause
        this.pauseButton = null;
        // Stop
        this.stopButton = null;
        // Restart
        this.restartButton = null;
        // Progress
        this.progressBar = null;
    }

    /**
     * @param {any} item
     */
    set setStream(item) {
        this.stream = item;
    }

    renderActions() {
        // Create record button
        const recordButton = document.createElement('div');//

        // Add record button classes
        recordButton.classList.add('custom_block-record_button-wrapper');//

        // Create the record inner button
        this.recButton = document.createElement('button');//
        this.recButton.classList.add('custom_block-record_button-inner');//
        // Add record button inner id
        this.recButton.setAttribute('id', 'recordButton');//

        // Create inner 'REC' text
        const rec = document.createElement('span');//
        rec.classList.add('rec_button-text');//
        rec.setAttribute('id', '__rec_button-text');//
        rec.innerText = 'REC';//

        // Create Stop button
        const stopButton = document.createElement('button');//
        const stopIcon = document.createElement('img');//
        stopIcon.src = '/storage/uploads/videoControls/stop-white.png';//
        stopButton.setAttribute('id', 'stopButton');//
        stopButton.appendChild(stopIcon);//


        // Create play-back button
        const playbackButton = document.createElement('button');//
        const buttonIcon = document.createElement('img');//
        buttonIcon.src = '/storage/uploads/videoControls/play-button-arrowhead-white.png';//
        playbackButton.setAttribute('id', 'playButton');//
        playbackButton.appendChild(buttonIcon);//


        // Create pause button
        const pauseButton = document.createElement('button');//
        const pauseIcon = document.createElement('img');//
        pauseIcon.src = '/storage/uploads/videoControls/pause-white.png';//
        pauseButton.setAttribute('id', 'pauseButton');//
        pauseButton.appendChild(pauseIcon);//
        // Add pause button classes


        // Create playback progress bar
        const progressBar = document.createElement('div');//
        progressBar.classList.add('cusomt_block-progress_bar');//
        // Add progress bar id
        progressBar.setAttribute('id', 'progressBar');//
        // Add progress bar classes

        const progressBarRecorded = document.createElement('div');//
        progressBarRecorded.classList.add('cusomt_block-progress_bar-done');//
        progressBarRecorded.setAttribute('id', 'progressBarDone');//
        progressBar.appendChild(progressBarRecorded);//

        // Create reset
        const resetPlayback = document.createElement('button');//
        const resetIcon = document.createElement('img');//
        resetIcon.src = '/storage/uploads/videoControls/undo-circular-arrow-white.png';//
        resetPlayback.setAttribute('id', 'resetPlyaback');//
        resetPlayback.appendChild(resetIcon);//


        // Countdown timer wrapper
        const countdown = document.createElement('div');//
        countdown.setAttribute('id', 'countdown');//
        countdown.classList.add('custom_block-countdown_wrapper');//

        // Create playback elapsed
        const timeElapsed = document.createElement('span');//
        // Add playback elapsed id
        timeElapsed.setAttribute('id', 'timeElapsed');//
        // Add playback elapsed classes
        timeElapsed.classList.add('time_elapsed-counter');//

        // Create playback remaining
        const timeRemaining = document.createElement('span');//
        // Add playback elapsed id
        timeRemaining.setAttribute('id', 'timeRemaining');//
        // Add playback elapsed classes


        // Create re-record button
        const reRecordButton = document.createElement('button');//
        // Add re-record button id
        reRecordButton.setAttribute('id', 'reRecordButton');//


        // Append children to parents
        recordButton.appendChild(this.recButton);
        this.recButton.appendChild(rec);

        return {
            recordButton,
            stopButton,
            playbackButton,
            pauseButton,
            progressBar,
            progressBarRecorded,
            resetPlayback,
            countdown,
            timeElapsed
        }

    }

    render() {
        // Set the container document
        const videoContainer = document.createElement('div');//
        // Add the classes
        videoContainer.classList.add('custom_block-video_container');//

        // Set the inner container
        const videoContainerInner = document.createElement('div');//
        // Add the classes
        videoContainerInner.classList.add('custom_block-video_container-inner');//

        // Set the video recorder
        const videoPlayer = document.createElement('video');//
        // Set the classes
        videoPlayer.classList.add('custom_block-video');//
        // Set the id
        videoPlayer.setAttribute('id', 'video_player');//

        // Set the video playback container
        this.videoPlayback = document.createElement('video');//
        // Set classes
        this.videoPlayback.classList.add('custom_block-video_playback');//
        this.videoPlayback.style.display = 'none';//
        // Set id
        this.videoPlayback.setAttribute('id', 'video_playback');//

        // Create a container to float in the block
        const controlContainer = document.createElement('div');//
        controlContainer.classList.add('cusomt_block-controls_container');//
        controlContainer.style.display = 'none';//


        // Get the controls
        const controls = this.renderActions();

        // Append into the parent
        videoContainer.appendChild(videoContainerInner);//
        videoContainerInner.appendChild(videoPlayer);//
        videoContainerInner.appendChild(controls.recordButton);//
        videoContainerInner.appendChild(controls.countdown);//
        videoContainerInner.appendChild(controlContainer);//


        // TODO: Make this all dynamic etc
        videoContainerInner.appendChild(this.videoPlayback);//

        controlContainer.appendChild(controls.playbackButton);//
        controlContainer.appendChild(controls.pauseButton);//
        controlContainer.appendChild(controls.progressBar);//
        controlContainer.appendChild(controls.timeElapsed);//
        controlContainer.appendChild(controls.stopButton);//
        controlContainer.appendChild(controls.resetPlayback);//


        /**
         * Get permissions for the camera
         */

        navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true
        }).then(stream => {
            // const video = videoPlayer;

            if ('srcObject' in videoPlayer) {
                this.stream = stream;
                videoPlayer.srcObject = this.stream;
                this.mediaRecorder = new MediaRecorder(this.stream, { mimeType: 'video/webm' });
                console.log(this.mediaRecorder);
            } else {
                videoPlayer.src = window.URL.createObjectURL(this.stream);
            };

            videoPlayer.onloadedmetadata = (e) => {
                videoPlayer.play();
            };

        });

        const startRecording = () => {
            /**
             * Set the required countdown timer variables
             */
            const fullDashArray = 283;
            const warningThreshold = 10;
            const alertThreshold = 5;

            const colorCodes = {
                info: {
                    color: 'green',
                },
                warning: {
                    color: 'orange',
                    threshold: warningThreshold
                },
                alert: {
                    color: 'red',
                    threshold: alertThreshold
                }
            };

            const timeLimit = 3;
            let timePassed = 0;
            let timeLeft = timeLimit;
            let timerInterval = null;
            let remainingPathColor = colorCodes.info.color;

            const startTimer = () => {
                timerInterval = setInterval(() => {
                    timePassed = timePassed += 1;
                    timeLeft = timeLimit - timePassed;
                    document.getElementById("base-timer-label").innerHTML = formatTime(
                        timeLeft
                    );
                    setCircleDasharray();
                    setRemainingPathColor(timeLeft);

                    if (timeLeft === 0) {
                        onTimesUp();
                    }
                }, 1000);
            };

            const formatTime = (time) => {
                const minutes = Math.floor(time / 60);
                let seconds = time % 60;
                console.log(seconds);
                return `0${seconds}`;
            };

            const limit = this.recordingLengthMS;
            const int = 1;
            let count = 0;
            const progTimer = () => {
                count++;
                let time = limit - count;
                let percentage = (count / limit) * 1000;

                let seconds = Math.trunc(count / 100);
                if (seconds <= 9) {
                    seconds = `0${seconds}`;
                };

                if (count == 6000 || !this.recording) {
                    count = 0;
                    this.mediaRecorder.stop();
                    return;
                };

                controls.timeElapsed.innerText = `00:${seconds}`;
                controls.progressBarRecorded.style.width = `${percentage}%`;
                setTimeout(progTimer, int);
            };

            const onTimesUp = () => {
                clearInterval(timerInterval);
                controls.countdown.style.display = 'none';
                controlContainer.style.display = 'flex';
                // videoPlayer.setAttribute('controls', true);
                this.mediaRecorder.start();
                this.recording = true;
                progTimer();
            };

            const setRemainingPathColor = (timeLeft) => {
                const { alert, warning, info } = colorCodes;
                if (timeLeft <= alert.threshold) {
                    document
                    .getElementById("base-timer-path-remaining")
                    .classList.remove(warning.color);
                    document
                    .getElementById("base-timer-path-remaining")
                    .classList.add(alert.color);
                } else if (timeLeft <= warning.threshold) {
                    document
                    .getElementById("base-timer-path-remaining")
                    .classList.remove(info.color);
                    document
                    .getElementById("base-timer-path-remaining")
                    .classList.add(warning.color);
                }
            }

            const  calculateTimeFraction = () => {
                const rawTimeFraction = timeLeft / timeLimit;
                return rawTimeFraction - (1 / timeLimit) * (1 - rawTimeFraction);
            }

            const setCircleDasharray = () => {
                const circleDasharray = `${(
                    calculateTimeFraction() * fullDashArray
                ).toFixed(0)} 283`;
                document
                    .getElementById("base-timer-path-remaining")
                    .setAttribute("stroke-dasharray", circleDasharray);
            }

            controls.recordButton.style.display = 'none';
            controls.countdown.style.display = 'flex';
            controls.countdown.innerHTML = `
                <div class="base-timer">
                    <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <g class="base-timer__circle">
                        <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
                        <path
                            id="base-timer-path-remaining"
                            stroke-dasharray="283"
                            class="base-timer__path-remaining ${remainingPathColor}"
                            d="
                            M 50, 50
                            m -45, 0
                            a 45,45 0 1,0 90,0
                            a 45,45 0 1,0 -90,0
                            "
                        ></path>
                        </g>
                    </svg>
                    <span id="base-timer-label" class="base-timer__label">${formatTime(
                        timeLeft
                    )}</span>
                </div>
            `;

            startTimer();
        }

        /**
         * Record Button
         */
        this.api.listeners.on(controls.recordButton, 'click', startRecording);

        /**
         * Pause Button
         */
        this.api.listeners.on(controls.pauseButton, 'click', (e, block) => {
            console.log('pause button clicked');
            this.videoPlayback.pause();
        });

        /**
         * Play Button
         */
        this.api.listeners.on(controls.playbackButton, 'click', (e, block) => {
            console.log('play button clicked');
            this.videoPlayback.play();
        });

        /**
         * Stop Button
         */
        this.api.listeners.on(controls.stopButton, 'click', (e, block) => {

            if (this.videoPlayback.src) {
                this.videoPlayback.stop();
            } else {
                this.recording = false;
                this.mediaRecorder.stop();
                videoPlayer.style.display = 'none';
                this.videoPlayback.style.display = 'block';

                // Set the playback element
                this.mediaRecorder.addEventListener('dataavailable', async event => {
                    const buff = await event.data.arrayBuffer();
                    this.videoPlayback.src = URL.createObjectURL( new Blob( [buff] ));
                    this.videoPlayback.play();
                });
            }

        });

        /**
         * Reset Record Button
         */
        this.api.listeners.on(controls.resetPlayback, 'click', (e, block) => {
            console.log('reset button clicked');
            this.videoPlayback.removeAttribute('src');
            videoPlayer.style.display = 'block';
            this.videoPlayback.style.display = 'none';
            controls.countdown.style.display = 'flex';
            controlContainer.style.display = 'none';
            document.getElementById("base-timer-label").remove();
            controls.recordButton.style.display = 'block';
        });


        return videoContainer;
    }

    save() {
        console.log(this.videoPlayback.src);
        const blob = this.videoPlayback.src;
        const fileName = 'hahahahahaha';

        const newFile = new File(
            [blob],
            fileName + '.mp4',
            { type: 'video/mp4' }
        );

        const saveFile = {
            file: newFile
        }

        console.log(saveFile);

        return saveFile;
    }
}

export default VideoRecorder;
