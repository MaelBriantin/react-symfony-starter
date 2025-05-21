import { useState } from "react";
import useFetch from "../../hooks/useFetch";
import { useNavigate } from "react-router";
import useUserStore from "@/stores/useUserStore";
import { User } from "@/types/user";

interface LoginFormProps {
  username: string;
  password: string;
}

const LoginForm = () => {
  const { loading, error, fetchData } = useFetch<User, LoginFormProps>();
  const { setUser } = useUserStore();
  const redirect = useNavigate();
  const [loginInfo, setLoginInfo] = useState<LoginFormProps>({
    username: "test@example.com",
    password: "Password123!",
  });

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const user = await fetchData({
      url: "/auth/login",
      method: "POST",
      body: loginInfo
    });
    if (!error) {
      setUser(user);
      redirect("/");
    }
  }

  return (
    <div className="dark:bg-neutral-900 dark:text-white bg-white text-black min-h-screen flex items-center justify-center font-geist-mono flex-col gap-2 w-full p-6">
      <h1>Login Form</h1>
      <form className="flex flex-col gap-4 w-full max-w-sm" onSubmit={handleSubmit}>
        <div className="flex flex-col">
          <label htmlFor="email">Email:</label>
          <input
            type="email"
            id="email"
            name="email"
            required
            className="border-2 border-gray-300 rounded-md p-2 focus:outline-none focus:border-blue-500"
            value={loginInfo.username}
            onChange={e => setLoginInfo({
              ...loginInfo,
              username: e.target.value
            })}
          />
        </div>
        <div className="flex flex-col">
          <label htmlFor="password">Password:</label>
          <input
            type="password"
            id="password"
            name="password"
            required
            className="border-2 border-gray-300 rounded-md p-2 focus:outline-none focus:border-blue-500"
            value={loginInfo.password}
            onChange={e => setLoginInfo({
              ...loginInfo,
              password: e.target.value
            })}
          />
        </div>
        {error && <div className="text-red-500">{error.message}</div>}
        <button
          type="submit"
          className="bg-blue-500 text-white rounded-md p-2 mt-4 hover:bg-blue-600"
          disabled={loading}
        >
          {loading ? "Connexion..." : "Login"}
        </button>
      </form>
    </div>
  );
}

export default LoginForm;