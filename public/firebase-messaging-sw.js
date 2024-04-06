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

// const firebaseConfig = {
//      apiKey: "AIzaSyBvqlnvze8StG22p3oulB9foQm1dO3cuu8",
//      authDomain: "megaz-erp.firebaseapp.com",
//      projectId: "megaz-erp",
//      storageBucket: "megaz-erp.appspot.com",
//      messagingSenderId: "609911397064",
//      appId: "1:609911397064:web:ec83c2db74e8ece72463fd"
// };

const firebaseConfig = {
    apiKey: "AIzaSyA_RiMlzMDYxKF_iT8wBxPAW3NpKxEPxas",
    authDomain: "megaz-project.firebaseapp.com",
    projectId: "megaz-project",
    storageBucket: "megaz-project.appspot.com",
    messagingSenderId: "25949618078",
    appId: "1:25949618078:web:86369862c2a1fc084ca08f",
    measurementId: "G-M6MF9D78DD"
  };

//   const firebaseConfig = {
//     apiKey: "AIzaSyAyQupmlsJiHgzdJaTH8Os4uq9Wij268YA",
//     authDomain: "megaz-test.firebaseapp.com",
//     projectId: "megaz-test",
//     storageBucket: "megaz-test.appspot.com",
//     messagingSenderId: "105252991244",
//     appId: "1:105252991244:web:e4e4234ee6c8a2e6c25929",
//     measurementId: "G-7832JEYHFH"
//   };

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

self.addEventListener("fetch", (event) => {
    // Handle fetch events here
    console.log('fetching');
});
