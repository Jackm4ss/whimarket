import React from 'react';
import { cn } from '@/lib/utils';

interface CardProps extends React.HTMLAttributes<HTMLDivElement> {
  hoverEffect?: boolean;
}

export const Card: React.FC<CardProps> = ({
  children,
  hoverEffect = true,
  className,
  ...props
}) => {
  return (
    <div
      className={cn(
        'bg-white rounded-2xl sm:rounded-3xl border border-gray-100 shadow-[0_4px_16px_rgba(0,0,0,0.04)]',
        hoverEffect && 'hover:shadow-[0_10px_26px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300',
        className
      )}
      {...props}
    >
      {children}
    </div>
  );
};
