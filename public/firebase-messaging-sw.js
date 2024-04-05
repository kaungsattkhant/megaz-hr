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
     apiKey: "AIzaSyBvqlnvze8StG22p3oulB9foQm1dO3cuu8",
     authDomain: "megaz-erp.firebaseapp.com",
     projectId: "megaz-erp",
     storageBucket: "megaz-erp.appspot.com",
     messagingSenderId: "609911397064",
     appId: "1:609911397064:web:ec83c2db74e8ece72463fd"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

self.addEventListener("fetch", (event) => {
    // Handle fetch events here
    console.log('fetching');
});
