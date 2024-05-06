import './App.css';
import {BrowserRouter, Routes,Route} from "react-router-dom";
import NavbarContainer from './components/layout/NavbarContainer';
import Login from './pages/Login';
import Registration from './pages/Registration';
import Home from './pages/Home';
function App() {
  return (
<>
<BrowserRouter>
<NavbarContainer />
<Routes>
<Route path='/'>
<Route path='/login' element = {<Login />} />
<Route path='/signup' element = {<Registration />}/>
<Route path='/' element = {<Home />} />
</Route>
</Routes>
</BrowserRouter>
</>
  );
}

export default App;
