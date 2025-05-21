import useUserStore from "@/stores/useUserStore";
import useFetch from "../hooks/useFetch";
import { User } from "@/types/user";
import { useEffect } from "react";

interface Env {
  apiUrl: string;
  clientUrl: string;
}

const About = () => {
  const { data: env } = useFetch<Env>('/config/env');
  const { error, data } = useFetch<User>('/auth/check');
  const { fetchData: logout } = useFetch(); // hook pour logout

  const handleLogout = async () => {
    await logout({ url: '/auth/logout', method: 'GET' });
    setUser(null); // vide le user dans le store
  };
  const { user, setUser } = useUserStore();

  useEffect(() => {
    if (data && !user) {
      setUser(data);
    }
  }, [data, user, setUser]);

  
  return (
    <div className="dark:bg-neutral-900 dark:text-white bg-white text-black min-h-screen flex items-center justify-center font-geist-mono flex-col gap-2 w-full p-6">
      <button className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" onClick={handleLogout}>
        Logout
      </button>
      <h1 className="text-4xl font-bold">
        Welcome { user ? <span className="text-blue-500">{user.email}</span> : 'Guest' }, to this React and Symfony Starter Template
      </h1>
      <p className="mt-4">
        This is a simple starter template for React and Symfony.
      </p>
      <div className="m-4">
        It includes
        everything you need to get started with building a modern web application, including:
        <ul className="list-decimal list-inside">
          <li>React 19 as the frontend framework</li>
          <ul className="list-disc list-inside ml-6">
            <li>Vite as the build tool</li>
            <li>TypeScript</li>
            <li>Tailwind CSS with V4 Configuration</li>
            <li>React Router v7</li>
            <li>Zustand</li>
          </ul>
          <li>Symfony as the backend framework (Json API)</li>
          <ul className="list-disc list-inside ml-6">
            <li>Hexagonal Architecture</li>
            <li>Doctrine as the ORM</li>
            <li>Authentication with JWT and HTTP Only cookies</li>
          </ul>
          <li>MySQL as the database</li>
          <li>Caddy as a web server</li>
        </ul>
      </div>
      <p className="mt-4">
        All is dockerized and Taskfile is used to manage the development environment and help you with common tasks.
      </p>
      <p className="mt-4">
        You can access the Symfony API at <a href={env?.apiUrl} className="text-blue-500 hover:underline">{env?.apiUrl}</a>
      </p>
      <br />
      <p className="mt-6 text-xl font-bold text-green-400">
        Happy Hacking!
      </p>
    </div>
  )
}

export default About;
