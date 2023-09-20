import Axios from "axios";
import {axiosHeader} from "./config";

export const crudCourse = async (data)=> {
    let request = Axios({
        headers : axiosHeader(),
        method : "post", data : data, url : `${window.origin}/api/course`
    });
    return Promise.resolve(request);
}
