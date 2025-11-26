importScripts("https://www.gstatic.com/firebasejs/10.10.0/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.10.0/firebase-messaging-compat.js");

if ("serviceWorker" in navigator) {
    navigator.serviceWorker
    .register("/firebase-messaging-sw.js")
    .then((registration) => {
        console.log("Service Worker registered with scope:", registration.scope);
    })
    .catch((error) => {
        console.error("Service Worker registration failed:", error);
    });
}

const firebaseConfig = {
  apiKey: "AIzaSyCvUQJCbpIMC02ceunLnZI7Dyq5VrEjw2M",
  authDomain: "megaz-78046.firebaseapp.com",
  projectId: "megaz-78046",
  storageBucket: "megaz-78046.firebasestorage.app",
  messagingSenderId: "144736503266",
  appId: "1:144736503266:web:50908d34738ad48d93a5bb",
  measurementId: "G-20WGQM0S3T"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

self.addEventListener("fetch", (event) => {
    // Handle fetch events here
    // console.log('fetching');
});
