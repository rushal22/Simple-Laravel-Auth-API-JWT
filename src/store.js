import { legacy_createStore , applyMiddleware, compose } from "redux";
import rootReducer from './reactredux/reducers';
import {thunk}  from "redux-thunk";

const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
const enhancer = composeEnhancers(applyMiddleware(thunk));



const loadState = () => {
    try {
        const serializedState = localStorage.getItem('ReduxStore');
        if (serializedState === null) {
          return undefined;
        }
        return JSON.parse(serializedState);
      } catch (error) {
        console.error('Error loading state from localStorage:', error);
        return undefined;
      }
}
const persistedState = loadState()
const store = legacy_createStore(rootReducer, persistedState ,  enhancer);


const updateReduxStore = () => {
    // localStorage.setItem('ReduxStore' , JSON.stringify(store.getState()))
    try {
        const serializedState = JSON.stringify(store.getState());
        localStorage.setItem('ReduxStore', serializedState);
      } catch (error) {
        console.error('Error saving state to localStorage:', error);
      }
}

store.subscribe(() => {
    updateReduxStore(store)
})
// const store = legacy_createStore(rootReducer ,window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__(),  applyMiddleware(thunk))
export default store
