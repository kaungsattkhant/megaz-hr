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
    apiKey: "AIzaSyA_RiMlzMDYxKF_iT8wBxPAW3NpKxEPxas",
    authDomain: "megaz-project.firebaseapp.com",
    projectId: "megaz-project",
    storageBucket: "megaz-project.appspot.com",
    messagingSenderId: "25949618078",
    appId: "1:25949618078:web:86369862c2a1fc084ca08f",
    measurementId: "G-M6MF9D78DD"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

self.addEventListener("fetch", (event) => {
    // Handle fetch events here
    // console.log('fetching');
});
