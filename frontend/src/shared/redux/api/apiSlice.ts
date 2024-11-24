import {createApi, fetchBaseQuery} from '@reduxjs/toolkit/query/react'

const apiSlice = createApi({
	reducerPath: 'API',
	baseQuery: fetchBaseQuery({baseUrl: '/api'}),
	endpoints: () => ({}),
})

export {
	apiSlice,
}