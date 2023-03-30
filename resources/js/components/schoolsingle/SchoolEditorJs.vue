<script setup>
import EditorJS from '@editorjs/editorjs';
/**
 * EditorJS Plugins
 */
import Header from '@editorjs/header';
import Paragraph from '@editorjs/paragraph';
import List from '@editorjs/list';
import SimpleImage from '@editorjs/simple-image';
import FontSize from 'editorjs-inline-font-size-tool';
import VideoRecorder from '../../constants/customVideoBlock';

import { onMounted } from 'vue';

const props = defineProps({
    existingData:{
        type: Object,
        required: false
    },
    editMode:{
        type: Boolean,
        required: false
    }
})

const emits = defineEmits(['sendSchoolData'])

const editorJsTools = {
    videoRecorder: {
        class: VideoRecorder,
        // inlineToolbar: true,
        // autofocus: true,
        // config: {
        //     placeholder: 'Record Video'
        // },
    },
    header:{
        class: Header,
        inlineToolbar: true,
        config:{
            placeholder: "Insert Header"
        }
    },
    fontSize: FontSize,

    paragraph: {
        class: Paragraph,
        inlineToolbar: true,
        config: {
            placeholder: "Paragraph"
        },
        toolbox: [
            {
                icon : '<svg width="25" height="19" viewBox="0 0 25 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.70313 18.5V3.875H0.890625V0.5H16.0031V3.875H10.1906V18.5H6.70313ZM17.1656 18.5V9.5H13.6781V6.125H24.1406V9.5H20.6531V18.5H17.1656Z" fill="white"/></svg>',
                title : 'Text'
            }
        ]
    },
    list: {
        class: List,
        inlineToolbar: true,
    },
    image: SimpleImage

}

const editor = new EditorJS({
    holder: 'editorJs',
    tools: editorJsTools,
    autofocus: true,
    onReady: () => {
        console.log('EditorJs in the chamber and ready to be emptied 🔫')
        if(props.existingData){
            for(let block of props.existingData.blocks){
                editor.blocks.insert(block.type, block.data)
            }
            // Add an auto delete first block due editorjs automatically create 1 empty block on init
            // when props exists, delete first empty block to ensure consistent representation of data
            editor.blocks.delete(0)

            // editor.blocks.forEach(block => {
            //     console.log(block);
            // });
        }
    },
    onChange: async (api, event) =>{
        // console.log(api)
        // console.log(api.blocks)
        // console.log(event)
        // console.log(event.detail.target.holder.innerText)
        // console.log(event.detail.index + ' is the index emitting the onchange')
        const blockCount = editor.blocks.getBlocksCount();

        // const blockElement = [];

        for (let i = 0; i < blockCount; i++) {
            const block = editor.blocks.getBlockByIndex(i);
            if (block.name === 'videoRecorder') {
                let blockElement = block.holder;
                // blockElement.push(block.holder);
                let recordButton = blockElement.querySelector('#recordButton');
                let stopButton = blockElement.querySelector('#stopButton');

                // Start recording
                // const video = document.getElementById('video_player');
                // console.log(video);
                let cameraStream = null;
                let blobs_recorded = [];
                let downloadLink = document.createElement('button');



                cameraStream = navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                let allMediaDevices = navigator.mediaDevices;
                if (!allMediaDevices || !allMediaDevices.getUserMedia) {
                    console.log('Get user media not supported');
                    return;
                }

                allMediaDevices.getUserMedia({
                    audio: true,
                    video: true
                }).then(stream => {
                    let video = document.getElementById('video_player');

                    if ('srcObject' in video) {
                        video.srcObject = stream;

                        let mediaRecorder = new MediaRecorder(stream, { mimeType: 'video/webm' });

                        recordButton.classList.remove('hidden');
                        recordButton.classList.add('flex');

                        recordButton.addEventListener('click', async (e) => {
                            recordButton.style.display = 'none';

                            const FULL_DASH_ARRAY = 283;
                            const WARNING_THRESHOLD = 10;
                            const ALERT_THRESHOLD = 5;

                            const COLOR_CODES = {
                                info: {
                                    color: "green"
                                },
                                warning: {
                                    color: "orange",
                                    threshold: WARNING_THRESHOLD
                                },
                                alert: {
                                    color: "red",
                                    threshold: ALERT_THRESHOLD
                                }
                            }

                            const TIME_LIMIT = 3;
                            let timePassed = 0;
                            let timeLeft = TIME_LIMIT;
                            let timerInterval = null;
                            let remainingPathColor = COLOR_CODES.info.color;

                            document.getElementById('countdown').classList.remove('hidden');
                            document.getElementById('countdown').classList.add('flex');

                            document.getElementById('countdown').innerHTML = `
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

                            function onTimesUp() {
                                clearInterval(timerInterval);
                                document.getElementById('countdown').classList.add('hidden');
                                mediaRecorder.start();
                            }

                            function startTimer() {
                                timerInterval = setInterval(() => {
                                    timePassed = timePassed += 1;
                                    timeLeft = TIME_LIMIT - timePassed;
                                    document.getElementById("base-timer-label").innerHTML = formatTime(
                                    timeLeft
                                    );
                                    setCircleDasharray();
                                    setRemainingPathColor(timeLeft);

                                    if (timeLeft === 0) {
                                    onTimesUp();
                                    }
                                }, 1000);
                            }

                            function formatTime(time) {
                                const minutes = Math.floor(time / 60);
                                // if (time < 10) {
                                //     time = 0 + time
                                // }
                                let seconds = time % 60;



                                return `${minutes}:0${seconds}`;
                            }

                            function setRemainingPathColor(timeLeft) {
                                const { alert, warning, info } = COLOR_CODES;
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

                            function calculateTimeFraction() {
                                const rawTimeFraction = timeLeft / TIME_LIMIT;
                                return rawTimeFraction - (1 / TIME_LIMIT) * (1 - rawTimeFraction);
                            }

                            function setCircleDasharray() {
                                const circleDasharray = `${(
                                    calculateTimeFraction() * FULL_DASH_ARRAY
                                ).toFixed(0)} 283`;
                                document
                                    .getElementById("base-timer-path-remaining")
                                    .setAttribute("stroke-dasharray", circleDasharray);
                            }



                        });

                        //****************************************************//
                        // stopButton.classList.remove('hidden');
                        // stopButton.classList.remove('flex');



                        // mediaRecorder.addEventListener('dataavailable', function(e) {
                        //     blobs_recorded.push(e.data);
                        // });

                        // event : recording stopped & all blobs sent
                        // mediaRecorder.addEventListener('stop', function() {
                        //     // create local object URL from the recorded video blobs
                        //     let video_local = URL.createObjectURL(new Blob(blobs_recorded, { type: 'video/webm' }));
                        //     downloadLink.href = video_local;
                        // });


                        // mediaRecorder.start();
                        console.log(mediaRecorder.state);

                        stopButton.addEventListener('click', function() {
                            mediaRecorder.stop();
                        });
                    } else {
                        video.src = window.URL.createObjectURL(stream);
                    }

                    video.onloadedmetadata = (e) => {
                        video.play();
                    };



                })





                // video.srcObject = cameraStream;

                // let mediaRecorder = new MediaRecorder(cameraStream, { mimeType: 'video/webm' });

                // console.log(mediaRecorder.state);
                // Start recording
                // recordButton.addEventListener('click', async (e) => {
                //     const FULL_DASH_ARRAY = 283;
                //     const WARNING_THRESHOLD = 10;
                //     const ALERT_THRESHOLD = 5;

                //     const COLOR_CODES = {
                //         info: {
                //             color: "green"
                //         },
                //         warning: {
                //             color: "orange",
                //             threshold: WARNING_THRESHOLD
                //         },
                //         alert: {
                //             color: "red",
                //             threshold: ALERT_THRESHOLD
                //         }
                //     }

                //     const TIME_LIMIT = 3;
                //     let timePassed = 0;
                //     let timeLeft = TIME_LIMIT;
                //     let timerInterval = null;
                //     let remainingPathColor = COLOR_CODES.info.color;

                //     document.getElementById('countdown').classList.remove('hidden');
                //     document.getElementById('countdown').classList.add('flex');

                //     document.getElementById('countdown').innerHTML = `
                //         <div class="base-timer">
                //             <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                //                 <g class="base-timer__circle">
                //                 <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
                //                 <path
                //                     id="base-timer-path-remaining"
                //                     stroke-dasharray="283"
                //                     class="base-timer__path-remaining ${remainingPathColor}"
                //                     d="
                //                     M 50, 50
                //                     m -45, 0
                //                     a 45,45 0 1,0 90,0
                //                     a 45,45 0 1,0 -90,0
                //                     "
                //                 ></path>
                //                 </g>
                //             </svg>
                //             <span id="base-timer-label" class="base-timer__label">${formatTime(
                //                 timeLeft
                //             )}</span>
                //         </div>
                //     `;

                //     startTimer();

                //     function onTimesUp() {
                //         clearInterval(timerInterval);
                //         document.getElementById('countdown').classList.add('hidden');
                //     }

                //     function startTimer() {
                //         timerInterval = setInterval(() => {
                //             timePassed = timePassed += 1;
                //             timeLeft = TIME_LIMIT - timePassed;
                //             document.getElementById("base-timer-label").innerHTML = formatTime(
                //             timeLeft
                //             );
                //             setCircleDasharray();
                //             setRemainingPathColor(timeLeft);

                //             if (timeLeft === 0) {
                //             onTimesUp();
                //             }
                //         }, 1000);
                //     }

                //     function formatTime(time) {
                //         const minutes = Math.floor(time / 60);
                //         // if (time < 10) {
                //         //     time = 0 + time
                //         // }
                //         let seconds = time % 60;



                //         return `${minutes}:0${seconds}`;
                //     }

                //     function setRemainingPathColor(timeLeft) {
                //         const { alert, warning, info } = COLOR_CODES;
                //         if (timeLeft <= alert.threshold) {
                //             document
                //             .getElementById("base-timer-path-remaining")
                //             .classList.remove(warning.color);
                //             document
                //             .getElementById("base-timer-path-remaining")
                //             .classList.add(alert.color);
                //         } else if (timeLeft <= warning.threshold) {
                //             document
                //             .getElementById("base-timer-path-remaining")
                //             .classList.remove(info.color);
                //             document
                //             .getElementById("base-timer-path-remaining")
                //             .classList.add(warning.color);
                //         }
                //     }

                //     function calculateTimeFraction() {
                //         const rawTimeFraction = timeLeft / TIME_LIMIT;
                //         return rawTimeFraction - (1 / TIME_LIMIT) * (1 - rawTimeFraction);
                //     }

                //     function setCircleDasharray() {
                //         const circleDasharray = `${(
                //             calculateTimeFraction() * FULL_DASH_ARRAY
                //         ).toFixed(0)} 283`;
                //         document
                //             .getElementById("base-timer-path-remaining")
                //             .setAttribute("stroke-dasharray", circleDasharray);
                //     }

                //     //****************************************************//
                //     recordButton.style.display = 'none';
                //     stopButton.classList.remove('hidden');
                //     stopButton.classList.remove('flex');

                //     mediaRecorder.addEventListener('dataavailable', function(e) {
                //         blobs_recorded.push(e.data);
                //     });

                //     // event : recording stopped & all blobs sent
                //     mediaRecorder.addEventListener('stop', function() {
                //         // create local object URL from the recorded video blobs
                //         let video_local = URL.createObjectURL(new Blob(blobs_recorded, { type: 'video/webm' }));
                //         downloadLink.href = video_local;
                //     });


                //     mediaRecorder.start();
                //         console.log(mediaRecorder.state);
                //     });

                //     stopButton.addEventListener('click', function() {
                //         mediaRecorder.stop();
                //     });

                }









            }
    }
});

const editorJsEvent = (customEvent) => {
    // customEvent.type
    switch(customEvent.type){
    case 'block-added':
        console.log('handler for blockadded')
        break;
    case 'block-changed':
        console.log('handler for block changed')
        break;
    }

}

const handleEditorSave = async () =>{
    await editor.save().then(outputData => {
        emits('sendSchoolData', outputData)
    }).catch(err =>{
        console.log('error has happened ' + err)
    })
}

defineExpose({
    handleEditorSave,
})

</script>
<template>
    <div
        id="editorJs"
        class="text-genericDark mt-8 rounded-lg editor flex-col"
    />
</template>
