import changeUserDetails from "./userDetail";
import logInMessage from "./user";

import { combineReducers } from "redux";

const rootReducer = combineReducers({
    changeUserDetails,
    logInMessage
})
export default rootReducer;
