

class VideoRecorder {
    static get toolbox() {
        return {
            title: 'Video Recorder',
            icon: '<?xml version="1.0" encoding="iso-8859-1"?><!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  --><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 332.804 332.804" style="enable-background:new 0 0 332.804 332.804;" xml:space="preserve"><g><g><g><path d="M330.804,171.002c-3.6-6.4-12-8.8-18.8-4.8l-45.6,26.4l-11.6,6.8v63.2l10.8,6.4c0.4,0,0.4,0.4,0.8,0.4l44.8,26c2,1.6,4.8,2.4,7.6,2.4c7.6,0,13.6-6,13.6-13.6v-53.6l0.4-52.8C332.804,175.402,332.404,173.002,330.804,171.002z"/><path d="M64.404,150.602c35.6,0,64.4-28.8,64.4-64.4c0-35.6-28.8-64.4-64.4-64.4s-64.4,28.8-64.4,64.4C-0.396,121.802,28.804,150.602,64.404,150.602z M64.404,59.802c14.8,0,26.4,12,26.4,26.4c0,14.8-12,26.4-26.4,26.4c-14.4,0-26.4-12-26.4-26.4C37.604,71.402,49.604,59.802,64.404,59.802z"/><path d="M227.604,154.202c-10.4,5.2-22,8.4-34.4,8.4c-15.2,0-29.6-4.4-41.6-12.4h-45.6c-12,8-26.4,12.4-41.6,12.4c-12.4,0-24-2.8-34.4-8.4c-9.2,5.2-15.6,15.6-15.6,26.8v97.6c0,18,14.8,32.4,32.4,32.4h164.4c18,0,32.4-14.8,32.4-32.4v-97.6C243.204,169.802,236.804,159.402,227.604,154.202z"/><path d="M193.204,150.602c35.6,0,64.4-28.8,64.4-64.4c0-35.6-28.8-64.4-64.4-64.4c-35.6,0-64.4,28.8-64.4,64.4C128.804,121.802,157.604,150.602,193.204,150.602z M193.204,59.802c14.8,0,26.4,12,26.4,26.4c0,14.8-12,26.4-26.4,26.4c-14.4,0-26.4-12-26.4-26.4C166.804,71.402,178.404,59.802,193.204,59.802z"/></g></g></g></svg>'
        }
    }

    constructor({ data, api }) {
        /**
         * EditorJS stuff
         */
        this.data = data;
        this.api = api;

        /**
         * Set states etc
         */
        this.recording = false;

        this.mediaRecorder = null;
        this.stream = null;
        this.videoLocal = null;

        /**
         * Setting some stuff up for the dom
         */
        this.videoContainer;
        this.videoPlayer;
        this.recordButton;
        this.countdown;
        this.videoContainerInner;

        /**
         * Progress and video controls
         */
        //Stop
        this.stopButton;
        this.stopIcon;
        //Play
        this.playbackButton;
        this.playbackIcon;
        // Pause
        this.pauseButton;
        this.pauseIcon;
        // Progress bar
        this.progressBar;
        this.progressBarInner;
        // Reset
        this.resetRecording;
        this.resetRecordingIcon;
        // Elapsed time
        this.elapsedTime;
        // Time remaining
        this.timeRemaining;

        /**
         * Countdown variables
         */
        this.countdownOptions = {
            fullDashArray: 283,
            warningThreshold: 10,
            alertThreshold: 5,
            colorCodes: {},
        }
        this.intervalOptions = {
            timeLeft: null,
            timeLimit: 3,
            timePassed: -1,
            interval: null,
            remainingPathColor: ''
        }
    }

    startTimer() {
        // handle time fraction calculations
        const calculateTimeFraction = () => {
            const rawTimeFraction = this.intervalOptions.timeLeft / this.intervalOptions.timeLimit;
            return rawTimeFraction - (1 / this.intervalOptions.timeLimit) * (1 - rawTimeFraction);
        };

        // handle control of the countown path
        const setCircleDasharray = () => {
            const circleDasharray = `${(
                calculateTimeFraction() * this.countdownOptions.fullDashArray
            ).toFixed(0)} 283`;
            document.getElementById('base-timer-path-remaining').setAttribute('stroke-dasharray', circleDasharray);
        };

        // handle path color according to time left
        const setRemainingPathColor = (timeLeft) => {
            const { alert, warning, info } = this.countdownOptions.colorCodes;
            const baseTimerPathRemaining = document.getElementById('base-timer-path-remaining');
            if (timeLeft <= alert.threshold) {
                baseTimerPathRemaining.classList.remove(warning.color);
                baseTimerPathRemaining.classList.add(alert.color);
            } else if (timeLeft <= warning.threshold) {
                baseTimerPathRemaining.classList.remove(info.color);
                baseTimerPathRemaining.classList.add(warning.color);
            }
        };

        // time formatter
        const formatTime = (time) => {
            console.log(time);
            // handle minutes here if needed
            let minutes = Math.floor(time / 60);
            let seconds = time % 60;
            return `0${seconds}`;
        };

        const limit = 6000;
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
            this.countdown.style.display = 'none';
            this.renderActions();

            this.mediaRecorder.start();
            this.recording = true;
            progTimer();
        }

        // set some DOM stuff up for later (like meal prep)
        this.countdown = document.createElement('div');
        this.countdown.setAttribute('id', 'countdown');
        this.countdown.classList.add('custom_block-countdown_wrapper');
        this.videoContainerInner.appendChild(this.countdown);

        console.log(this.intervalOptions.timeLeft);

        const countdownTimer = () => {
            this.intervalOptions.timePassed++;
            this.intervalOptions.timeLeft = this.intervalOptions.timeLimit - this.intervalOptions.timePassed;
            document.getElementById('base-timer-label').innerHTML = formatTime(this.intervalOptions.timeLeft);

            if (this.intervalOptions.timePassed === this.intervalOptions.timeLimit) {
                onTimesUp();
                return;
            }

            setTimeout(countdownTimer, 1000);
        }

        this.intervalOptions.remainingPathColor = this.countdownOptions.colorCodes.info.color;
        this.recordButton.style.display = 'none';
        this.countdown.style.display = 'flex';
        this.countdown.innerHTML = `
            <div class="base-timer">
                <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <g class="base-timer__circle">
                    <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
                    <path
                        id="base-timer-path-remaining"
                        stroke-dasharray="283"
                        class="base-timer__path-remaining ${this.intervalOptions.remainingPathColor}"
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
                    this.intervalOptions.timeLeft
                )}</span>
            </div>
        `;

        countdownTimer();
    }

    startCountdown() {
        this.startTimer();
    }

    // Setup listeners
    initialiseListeners() {
        // Record button click
        this.api.listeners.on(this.recordButton, 'click', () => {
            this.startTimer()
        });
    }

    // Recorder countdown
    renderCountdown() {
        this.countdownOptions.colorCodes = {
            info: {
                color: 'green',
            },
            warning: {
                color: 'orange',
                threshold: this.countdownOptions.warningThreshold
            },
            alert: {
                color: 'red',
                threshold: this.countdownOptions.alertThreshold
            }
        };
        this.intervalOptions.remainingPathColor = this.countdownOptions.colorCodes.info.color;
        this.recordButton = document.createElement('div');
    }

    // Setup mediaRecorder and camera permissions etc
    getMediaDevices() {
        navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true
        }).then(stream => {
            if ('srcObject' in this.videoPlayer) {
                this.stream = stream;
                this.videoPlayer.srcObject = this.stream;
                this.mediaRecorder = new MediaRecorder(this.stream, { mimeType: 'video/webm' });
            } else {
                this.videoPlayer.src = window.URL.createObjectURL(this.stream);
            }

            this.videoPlayer.onloadedmetadata = () => {
                this.videoPlayer.play();
            }
        }).catch(error => {
            console.log('There has been a problem with your camera or microphone');
            console.error(error);
        });
    }

    // Progress bar and controls
    renderActions() {
        // const recordButton = document.createElement('div');
        // recordButton.classList.add('custom_block-record_button-wrapper');

        this.stopButton;
        this.stopIcon;
        //Play
        this.playbackButton;
        this.playbackIcon;
        // Pause
        this.pauseButton;
        this.pauseIcon;
        // Progress bar
        this.progressBar;
        this.progressBarInner;
        // Reset
        this.resetRecording;
        this.resetRecordingIcon;
        // Elapsed time
        this.elapsedTime;
        // Time remaining
        this.timeRemaining;
    }

    render() {
        const controls = this.renderActions();

        // Add the recordButton
        const recButton = document.createElement('button');
        recButton.classList.add('custom_block-record_button-inner');
        recButton.setAttribute('id', 'recordButton');
        //set the record countdown text field
        const rec = document.createElement('span');
        rec.classList.add('rec_button-text');
        rec.setAttribute('id', '__rec_button-text');
        rec.innerText = 'REC';
        // Set the wrapper
        this.videoContainer = document.createElement('div');
        this.videoContainer.classList.add('custom_block-video_container');

        // Set the inner
        this.videoContainerInner = document.createElement('div');
        this.videoContainerInner.classList.add('custom_block-video_container-inner');

        // Set video element used while recording
        this.videoPlayer = document.createElement('video');
        this.videoPlayer.classList.add('custom_block-video');
        this.videoPlayer.setAttribute('id', 'video_player');

        this.videoContainer.appendChild(this.videoContainerInner);
        this.videoContainerInner.appendChild(this.videoPlayer);

        // Initiate the mediarecorder
        this.getMediaDevices();

        // Initiate the countdown and timer
        // this.renderCountdown();
        this.countdownOptions.colorCodes = {
            info: {
                color: 'green',
            },
            warning: {
                color: 'orange',
                threshold: this.countdownOptions.warningThreshold
            },
            alert: {
                color: 'red',
                threshold: this.countdownOptions.alertThreshold
            }
        };
        this.intervalOptions.remainingPathColor = this.countdownOptions.colorCodes.info.color;
        this.recordButton = document.createElement('div');
        this.countdown = document.createElement('div');
        this.countdown.setAttribute('id', 'countdown');
        this.countdown.classList.add('custom_block-countdown_wrapper');

        this.videoContainerInner.appendChild(this.recordButton);
        this.recordButton.classList.add('custom_block-record_button-wrapper');
        this.recordButton.appendChild(recButton);
        recButton.appendChild(rec);

        // prepare the listeners
        this.initialiseListeners();

        return this.videoContainer;
    }
}

export default VideoRecorder;
