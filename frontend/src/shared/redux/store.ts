import {configureStore} from '@reduxjs/toolkit'
import {subjectEntitySlice} from 'entities/subject'
import {userEntitySlice} from 'entities/user'
import {apiSlice} from './api'
import {authorizationMiddleware} from './middleware'

const store = configureStore({
	reducer: {
		userEntity: userEntitySlice.reducer,
		subjectEntity: subjectEntitySlice.reducer,
		[apiSlice.reducerPath]: apiSlice.reducer,
	},
	middleware: getDefaultMiddleware =>
		getDefaultMiddleware().concat(authorizationMiddleware),
})

type AppDispatch = typeof store.dispatch
type RootState = ReturnType<typeof store.getState>

export {
	store,
	type AppDispatch,
	type RootState,
}