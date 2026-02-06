import { projectId, publicAnonKey } from './supabase/info';

const BASE_URL = `https://${projectId}.supabase.co/functions/v1/make-server-d33cec4f`;

export const api = {
  fetch: async (endpoint: string, options: RequestInit = {}) => {
    const url = `${BASE_URL}${endpoint}`;
    const headers = {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${publicAnonKey}`,
      ...options.headers,
    };

    const response = await fetch(url, { ...options, headers });
    
    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`API Error: ${response.status} - ${errorText}`);
    }

    return response.json();
  },

  getCourses: async () => {
    return api.fetch('/courses');
  },

  createCourse: async (courseData: any) => {
    return api.fetch('/courses', {
      method: 'POST',
      body: JSON.stringify(courseData),
    });
  },

  getRegistrations: async () => {
    return api.fetch('/registrations');
  },

  createRegistration: async (regData: any) => {
    return api.fetch('/registrations', {
      method: 'POST',
      body: JSON.stringify(regData),
    });
  }
};
