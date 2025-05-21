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
    <nav className="flex gap-4 justify-end items-end w-full">
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
    </nav>
  );
}

export default NavBar;