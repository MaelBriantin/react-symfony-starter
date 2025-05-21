import { NavLink } from "react-router";

const NavBar = () => {
  return (
    <nav className="flex gap-4 justify-end items-end w-full">
      <NavLink to="/login" className="hover:underline">
        Login
      </NavLink>
    </nav>
  );
}

export default NavBar;