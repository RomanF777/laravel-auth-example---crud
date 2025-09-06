import React from 'react'
import { FaUserCircle } from 'react-icons/fa';

function UserAvatar({ 
  size = 'md', // sm, md, lg, xl, 2xl
  className = '' 
}) {
  // Размеры в пикселях для разных вариантов
  const sizeMap = {
    sm: 'h-6 w-6',
    md: 'h-8 w-8',
    lg: 'h-12 w-12',
    xl: 'h-16 w-16',
    '2xl': 'h-24 w-24'
  };

  // Цвета для разных размеров (опционально)
  const colorMap = {
    sm: 'text-gray-400',
    md: 'text-gray-500',
    lg: 'text-gray-600',
    xl: 'text-gray-700',
    '2xl': 'text-gray-800'
  };

  const sizeClass = sizeMap[size] || sizeMap.md;
  const colorClass = colorMap[size] || colorMap.md;

  return (
    <div className={`inline-flex ${className}`}>
      <FaUserCircle className={`${sizeClass} ${colorClass}`} />
    </div>
  )
}

export default UserAvatar;