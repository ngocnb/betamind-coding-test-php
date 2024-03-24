import axios from "axios";

export default axios.create({
    baseURL: "http://betamind.life-adventurer.com",
    // headers: {
    //     "Content-type": "application/json",
    // },
});
