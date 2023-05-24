class VideoRecorder {
    static get toolbox() {
        return {
            title: 'Video Recorder',
            icon: '<?xml version="1.0" encoding="iso-8859-1"?><!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  --><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 332.804 332.804" style="enable-background:new 0 0 332.804 332.804;" xml:space="preserve"><g><g><g><path d="M330.804,171.002c-3.6-6.4-12-8.8-18.8-4.8l-45.6,26.4l-11.6,6.8v63.2l10.8,6.4c0.4,0,0.4,0.4,0.8,0.4l44.8,26c2,1.6,4.8,2.4,7.6,2.4c7.6,0,13.6-6,13.6-13.6v-53.6l0.4-52.8C332.804,175.402,332.404,173.002,330.804,171.002z"/><path d="M64.404,150.602c35.6,0,64.4-28.8,64.4-64.4c0-35.6-28.8-64.4-64.4-64.4s-64.4,28.8-64.4,64.4C-0.396,121.802,28.804,150.602,64.404,150.602z M64.404,59.802c14.8,0,26.4,12,26.4,26.4c0,14.8-12,26.4-26.4,26.4c-14.4,0-26.4-12-26.4-26.4C37.604,71.402,49.604,59.802,64.404,59.802z"/><path d="M227.604,154.202c-10.4,5.2-22,8.4-34.4,8.4c-15.2,0-29.6-4.4-41.6-12.4h-45.6c-12,8-26.4,12.4-41.6,12.4c-12.4,0-24-2.8-34.4-8.4c-9.2,5.2-15.6,15.6-15.6,26.8v97.6c0,18,14.8,32.4,32.4,32.4h164.4c18,0,32.4-14.8,32.4-32.4v-97.6C243.204,169.802,236.804,159.402,227.604,154.202z"/><path d="M193.204,150.602c35.6,0,64.4-28.8,64.4-64.4c0-35.6-28.8-64.4-64.4-64.4c-35.6,0-64.4,28.8-64.4,64.4C128.804,121.802,157.604,150.602,193.204,150.602z M193.204,59.802c14.8,0,26.4,12,26.4,26.4c0,14.8-12,26.4-26.4,26.4c-14.4,0-26.4-12-26.4-26.4C166.804,71.402,178.404,59.802,193.204,59.802z"/></g></g></g></svg>',
        }
    }

    constructor({ data, api }) {
        this.api = api;
        this.data = data;

        /**
         * Set global mediarecorder and stream variables
         */
        this.mediaRecorder = null;
        this.stream = null;

        /**
         * Set all elements required
         * for the video controls
         */
        this.recordButton = document.createElement('div');
        this.recButton = document.createElement('button');
        this.rec = document.createElement('span');
        this.stopButton = document.createElement('button');
        this.stopIcon = document.createElement('img');
        this.playbackButton = document.createElement('button');
        this.buttonIcon = document.createElement('img');
        this.pauseButton = document.createElement('button');
        this.pauseIcon = document.createElement('img');
        this.progressBar = document.createElement('div');
        this.progressBarInner = document.createElement('div');
        this.resetPlayback = document.createElement('button');
        this.resetIcon = document.createElement('img');
        this.countdown = document.createElement('div');
        this.timeElapsed = document.createElement('span');
        this.timeRemaining = document.createElement('span');
        this.reRecordButton = document.createElement('button');

        /**
         * Set te elements for the video and containers
         */
        this.videoContainer = document.createElement('div');
        this.videoContainerInner = document.createElement('div');
        this.videoPlayer = document.createElement('video');
        this.videoPlayback = document.createElement('video');
        this.controlContainer = document.createElement('div');


        /**
         * Prepare any base values
         */
        this.stopIcon.src = '/storage/uploads/videoControls/stop-white.png';
        this.buttonIcon.src = '/storage/uploads/videoControls/play-button-arrowhead-white.png';
        this.pauseIcon.src = '/storage/uploads/videoControls/pause-white.png';
        this.resetIcon.src = '/storage/uploads/videoControls/undo-circular-arrow-white.png';
        this.rec.innerText = 'REC';

        /**
         * Predetermined styles
         */
        this.videoPlayback.style.display = 'none';
        this.controlContainer.style.display = 'none';

        /**
         * Countdown values
         */
        this.fullDashArray = 283;
        this.warningThreshold = 10;
        this.alertThreshold = 5;
        this.colorCodes = {};

        /**
         * All other values of things
         */
        this.countDownInterval = null;
        this.remainingPathColor = '';
        this.timeLeft = 0;

    }

    configureEls() {
        /**
         * Give the buttons their icons
         */
        this.stopButton.appendChild(this.stopIcon);
        this.playbackButton.appendChild(this.buttonIcon);
        this.pauseButton.appendChild(this.pauseIcon);
        this.progressBar.appendChild(this.progressBarInner);
        this.resetPlayback.appendChild(this.resetIcon);
        this.recordButton.appendChild(this.recButton);
        this.recButton.appendChild(this.rec);

        /**
         * Set all element classes
         */
        this.recordButton.classList.add('custom_block-record_button-wrapper');
        this.recButton.classList.add('custom_block-record_button-inner');
        this.rec.classList.add('rec_button-text');
        this.progressBar.classList.add('cusomt_block-progress_bar');
        this.progressBarInner.classList.add('cusomt_block-progress_bar-done');
        this.countdown.classList.add('custom_block-countdown_wrapper');
        this.timeElapsed.classList.add('time_elapsed-counter');
        this.videoContainer.classList.add('custom_block-video_container');
        this.videoContainerInner.classList.add('custom_block-video_container-inner');
        this.videoPlayer.classList.add('custom_block-video');
        this.videoPlayback.classList.add('custom_block-video_playback');
        this.controlContainer.classList.add('cusomt_block-controls_container');

        /**
         * Set all element ids
         */
        this.recButton.setAttribute('id', 'recordButton');
        this.rec.setAttribute('id', '__rec_button-text');
        this.stopButton.setAttribute('id', 'stopButton');
        this.playbackButton.setAttribute('id', 'playButton');
        this.pauseButton.setAttribute('id', 'pauseButton');
        this.progressBar.setAttribute('id', 'progressBar');
        this.progressBarInner.setAttribute('id', 'progressBarDone');
        this.resetPlayback.setAttribute('id', 'resetPlyaback');
        this.countdown.setAttribute('id', 'countdown');
        this.timeElapsed.setAttribute('id', 'timeElapsed');
        this.timeRemaining.setAttribute('id', 'timeRemaining');
        this.reRecordButton.setAttribute('id', 'reRecordButton');
        this.videoPlayer.setAttribute('id', 'video_player');
        this.videoPlayback.setAttribute('id', 'video_playback');
    }

    init() {
        navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true
        }).then(stream => {
            if ('srcObject' in this.videoPlayer) {
                this.stream = stream;
                this.videoPlayer.srcObject = this.stream;
                this.mediaRecorder = new MediaRecorder(this.stream, { mimeType: 'video/webm' });
                console.log(this.mediaRecorder);
            } else {
                this.videoPlayer.src = window.URL.createObjectURL(this.stream);
            };

            this.videoPlayer.onloadedmetadata = (e) => {
                this.videoPlayer.play();
            };
        });
    }

    static countDownTimer(countdown, int, count) {
        count++;
        let remaining = countdown - count;
        document.getElementById('base-timer-label').innerHTML = VideoRecorder.formatTime(remaining);
        VideoRecorder.setCircleDashArray(countdown, int, count);
        VideoRecorder.setRemainingPathColor(remaining);
        this.timeLeft = remaining;

        if (remaining === 0) {
            onTimesUp();
        };

        setTimeout(this.countDownTimer, int);
    }

    static formatTime(remaining) {
        const minutes = Math.floor(remaining / 60);
        let seconds = remaining % 60;
        return `0${seconds}`;
    }

    static progTimer(limit, int, count) {
        count++;
        let remaining = limit - count;
        let percentage = (count / time) * 1000;
        let seconds = Math.trunc(count / 100);
        if (seconds <= 9) {
            seconds = `0${seconds}`;
        }

        this.timeElapsed.innerText = `00:${seconds}`;
        this.progressBarInner.style.width = `${percentage}`;

        if (count === 6000) {
            count = 0;
            this.mediaRecorder.stop();
            return;
        }

        setTimeout(this.progTimer, int);
    }

    static onTimesUp() {
        let progLimit = 6000;
        let progInt = 1;
        let progCount = 0;
        this.remainingPathColor = this.colorCodes.info.color;

        this.countdown.style.display = 'none';
        this.controlContainer.style.display = 'flex';
        this.mediaRecorder.start();
        this.progTimer(progLimit, progInt, progCount);
    }

    static setRemainingPathColor(remaining) {
        this.colorCodes = {
            info: {
                color: 'green'
            },
            warning: {
                color: 'orange',
                threshold: this.warningThreshold
            },
            alert: {
                color: 'red',
                threshold: this.alertThreshold
            }
        };
        console.log(this.colorCodes);
        const { alert, warning, info } = this.colorCodes;
        if (remaining <= alert.threshold) {
            document.getElementById('base-timer-path-remaining').classList.remove(warning.color);
            document.getElementById('base-timer-path-remaining').classList.add(alert.color);
        } else if (remaining <= warning.threshold) {
            document.getElementById('base-timer-path-remaining').classList.remove(info.color);
            document.getElementById('base-timer-path-remaining').classList.add(warning.color);
        }
    }

    static calculateTimeFraction(countdown, int, count) {
        const rawTimeFraction = (countdown - count) / countdown;
        return rawTimeFraction - (1 / countdown) * (1 - rawTimeFraction);
    }

    static setCircleDashArray(countdown, int, count) {
        const circleDashArray = `${(
            this.calculateTimeFraction(countdown, int, count) * this.fullDashArray
        ).toFixed(0)} 283`;
        document.getElementById('base-timer-path-remaining').setAttribute('stroke-dasharray', circleDashArray);
    }

    start() {
        let countdown = 3;
        let int = 1000;
        let count = 0;

        document.getElementById('recordButton').style.display = 'none';
        document.getElementById('countdown').style.display = 'flex';
        // this.recordButton.style.display = 'none';
        // this.countdown.style.display = 'flex';
        document.getElementById('countdown').innerHTML = `
            <div class="base-timer">
                <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <g class="base-timer__circle">
                    <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
                    <path
                        id="base-timer-path-remaining"
                        stroke-dasharray="283"
                        class="base-timer__path-remaining ${this.remainingPathColor}"
                        d="
                        M 50, 50
                        m -45, 0
                        a 45,45 0 1,0 90,0
                        a 45,45 0 1,0 -90,0
                        "
                    ></path>
                    </g>
                </svg>
                <span id="base-timer-label" class="base-timer__label">${VideoRecorder.formatTime(this.timeLeft)}</span>
            </div>
        `;

        VideoRecorder.countDownTimer(countdown, int, count);
    }

    stop() {
        if (this.videoPlayback.src) {
            this.videoPlayback.stop();
        } else {
            this.mediaRecorder.stop();
            this.videoPlayer.style.display = 'none';
            this.videoPlayback.style.display = 'block';

            this.mediaRecorder.addEventListener('dataavailable', async event => {
                const arr = await event.data.arrayBuffer();
                this.videoPlayback.src = URL.createObjectURL( new Blob( [arr] ));
                this.videoPlayback.play();
            })
        }
    }

    reset() {
        this.videoPlayback.removeAttribute('src');
        this.videoPlayer.style.display = 'block';
        this.videoPlayback.style.display = 'none';
        this.countdown.style.display = 'flex';
        this.controlContainer.style.display = 'none';
        document.getElementById("base-timer-label").remove();
        this.controls.recordButton.style.display = 'block';
    }

    render() {
        this.videoContainer.appendChild(this.videoContainerInner);
        this.videoContainerInner.appendChild(this.videoPlayer);
        this.videoContainerInner.appendChild(this.recordButton);
        this.videoContainerInner.appendChild(this.countdown);
        this.videoContainerInner.appendChild(this.controlContainer);
        this.videoContainerInner.appendChild(this.videoPlayback);
        this.controlContainer.appendChild(this.playbackButton);
        this.controlContainer.appendChild(this.pauseButton);
        this.controlContainer.appendChild(this.progressBar);
        this.controlContainer.appendChild(this.timeElapsed);
        this.controlContainer.appendChild(this.stopButton);
        this.controlContainer.appendChild(this.resetPlayback);

        this.configureEls();

        /**
         * Initialise mediaDevices
         */
        this.init();

        this.api.listeners.on(this.recordButton, 'click', this.start);

        this.api.listeners.on(this.pauseButton, 'click', () => this.videoPlayback.pause());

        this.api.listeners.on(this.playbackButton, 'click', () => this.videoPlayback.play());

        this.api.listeners.on(this.stopButton, 'click', this.stop);

        this.api.listeners.on(this.resetPlayback, 'click', this.reset);

        return this.videoContainer;
    }

    save(blockContent) {
        return {
            file: blockContent.value
        }
    }
}

export default VideoRecorder;
