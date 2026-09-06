import React from 'react';
import { cn } from '@/lib/utils';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary' | 'outline' | 'amber';
  size?: 'sm' | 'md' | 'lg';
  asChild?: boolean;
}

export const Button: React.FC<ButtonProps> = ({
  children,
  variant = 'primary',
  size = 'md',
  className,
  ...props
}) => {
  const baseClasses = 'inline-flex items-center justify-center font-bold transition-all active:scale-[0.98] focus:outline-none';

  const variantClasses = {
    primary: 'bg-[#4F26A6] text-white hover:bg-[#3E1D85] shadow-sm border-2 border-[#4F26A6] hover:border-[#3E1D85]',
    secondary: 'bg-[#F3EEFF] text-[#4F26A6] hover:bg-[#4F26A6] hover:text-white',
    outline: 'bg-transparent border-2 border-[#4F26A6] text-[#4F26A6] hover:bg-[#4F26A6]/5',
    amber: 'bg-[#FDBA2D] hover:bg-[#F59E0B] text-[#111827] shadow-sm hover:shadow',
  }[variant];

  const sizeClasses = {
    sm: 'px-4 h-9 text-xs rounded-xl',
    md: 'px-5 h-11 text-sm rounded-xl',
    lg: 'px-7 h-[52px] text-[15.5px] rounded-xl',
  }[size];

  return (
    <button
      className={cn(baseClasses, variantClasses, sizeClasses, className)}
      {...props}
    >
      {children}
    </button>
  );
};
