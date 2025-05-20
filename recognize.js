const video = document.getElementById('video');
const nameField = document.getElementById('nameField');
const submitBtn = document.getElementById('submitBtn');

Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri('models'),
  faceapi.nets.faceRecognitionNet.loadFromUri('models'),
  faceapi.nets.faceLandmark68Net.loadFromUri('models')
]).then(startVideo);

function startVideo() {
  navigator.mediaDevices.getUserMedia({ video: {} })
    .then(stream => video.srcObject = stream)
    .catch(err => console.error(err));
}

video.addEventListener('playing', async () => {
  const displaySize = { width: video.width, height: video.height };
  faceapi.matchDimensions(video, displaySize);

  // Load stored descriptors from PHP
  const response = await fetch('get_face.php'); // this is still needed for loading faces
  const data = await response.json();

  const labeledDescriptors = data.map(user => {
    return new faceapi.LabeledFaceDescriptors(user.employee_id, [new Float32Array(JSON.parse(user.descriptor))]);
  });

  const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6);

  setInterval(async () => {
    const detections = await faceapi
      .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
      .withFaceLandmarks()
      .withFaceDescriptor();

    if (!detections) return;

    const bestMatch = faceMatcher.findBestMatch(detections.descriptor);

    if (bestMatch.label !== 'unknown') {
      console.log("Match:", bestMatch.label);

      nameField.value = bestMatch.label;
      submitBtn.click(); // submit the form automatically
    }
  }, 4000);
});
