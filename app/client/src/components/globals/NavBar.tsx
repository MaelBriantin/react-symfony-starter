import useFetch from "@/hooks/useFetch";
import useUserStore from "@/stores/useUserStore";
import { useLocation, NavLink, useNavigate } from "react-router";

const NavBar = () => {
  const { user, setUser } = useUserStore();
  const { fetchData: logout } = useFetch();
  const navigate = useNavigate();
  const location = useLocation();

  const loggedInUser = !!user;

  const handleLogout = async () => {
    await logout({
      url: '/auth/logout',
      method: 'GET'
    });
    console.log(location.pathname);
    if (location.pathname === '/') {
      setUser(null);
      window.location.reload();
    } else {
      setUser(null);
      navigate('/');
    }
  }

  return (
    <nav className="flex gap-4 justify-between items-end w-full"> 
      <section className="flex gap-4">
        {
          location.pathname !== '/' &&
          <NavLink to="/" className="hover:underline">
            Home
          </NavLink>
        }
      </section>
      <section className="flex gap-4">
        {
          !loggedInUser &&
          <NavLink to="/signup" className="hover:underline">
            SignUp
          </NavLink>
        }
        {
          !loggedInUser &&
          <NavLink to="/login" className="hover:underline">
            Login
          </NavLink>
        }
        {
          loggedInUser &&
          <div onClick={handleLogout} className="hover:underline hover:text-red-500 cursor-pointer select-none">
            Logout
          </div>
        }
      </section>
    </nav>
  );
}

export default NavBar;