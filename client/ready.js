export default function ready() {
  return new Promise((resolve) => {
    if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading"){
      resolve();
      return;
    }

    document.addEventListener('DOMContentLoaded', resolve);
  });
}
