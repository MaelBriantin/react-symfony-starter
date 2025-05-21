export enum InputType {
  TEXT = "text",
  EMAIL = "email",
  PASSWORD = "password",
  NUMBER = "number",
  DATE = "date",
}

interface InputProps {
  label: string;
  type: InputType;
  name: string;
  required: boolean;
  value: string;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
}

const Input = ({ label, type, name, required, value, onChange }: InputProps) => {
  return (
    <div className="flex flex-col">
        <label htmlFor="email">{label}</label>
          <input
            type={type}
            id={name}
            name={name}
            required={required}
            className="border-2 border-gray-300 rounded-md p-2 focus:outline-none focus:border-blue-500"
            value={value}
            onChange={onChange}
          />
        </div>
  );
}

export default Input;