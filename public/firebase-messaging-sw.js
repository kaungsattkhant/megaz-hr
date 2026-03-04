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
  apiKey: "AIzaSyD_Rc-Exu4Dx2luwa2M1q-6A-wg_wwJDH4",
  authDomain: "megaz-hros.firebaseapp.com",
  projectId: "megaz-hros",
  storageBucket: "megaz-hros.firebasestorage.app",
  messagingSenderId: "823941269115",
  appId: "1:823941269115:web:1b1b94177d8b1a8d3044de",
  measurementId: "G-J8JNQ4FGNS"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

self.addEventListener("fetch", (event) => {
    // Handle fetch events here
    // console.log('fetching');
});
