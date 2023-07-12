import{u as $,q as F,l as p,b as U,y as d,s as N,r as c,o as f,h as C,j as v,d as l,v as I,x as T,a as E,e as t,c as x,i as w}from"./app.ca5d1c04.js";const j={class:"grid grid-cols-3 gap-6"},q={class:"col-span-3 sm:col-span-1"},D={class:"col-span-3 sm:col-span-1"},P={key:0,class:"col-span-3 sm:col-span-2"},R={key:1,class:"col-span-3 sm:col-span-2"},z={name:"UserAccount"},J=Object.assign(z,{setup(G){$();const b="user/profile/",s=F(b),V=p("email"),y=p("username"),A=p("isSuperAdmin"),g=U(()=>!!(n.value||m.value)),_=U(()=>o.value?"verifyAccount":"updateAccount"),o=d(!1),n=d(!1),m=d(!1),O={username:y.value,email:V.value,existingEmailOtp:"",newEmailOtp:""},r=N({...O}),B=i=>{i.existingEmailVerification&&(o.value=!0,n.value=!0),i.newEmailVerification&&(o.value=!0,m.value=!0),i.profileUpdated&&(o.value=!1,n.value=!1,m.value=!1,r.existingEmailOtp="",r.newEmailOtp="")};return(i,e)=>{const u=c("BaseInput"),k=c("FormAction"),S=c("ParentTransition");return f(),C(S,{appear:"",visibility:!0},{default:v(()=>[l(k,{"no-card":"","init-url":b,action:t(_),"init-form":O,form:r,"after-submit":B,"stay-on":"",redirect:"Dashboard"},{title:v(()=>[I(T(i.$trans("user.profile.account")),1)]),default:v(()=>[E("div",j,[E("div",q,[l(u,{disabled:t(g)||t(A),type:"text",modelValue:r.username,"onUpdate:modelValue":e[0]||(e[0]=a=>r.username=a),name:"username",label:i.$trans("user.profile.props.username"),error:t(s).username,"onUpdate:error":e[1]||(e[1]=a=>t(s).username=a)},null,8,["disabled","modelValue","label","error"])]),E("div",D,[l(u,{disabled:t(g),type:"text",modelValue:r.email,"onUpdate:modelValue":e[2]||(e[2]=a=>r.email=a),name:"email",label:i.$trans("user.profile.props.email"),error:t(s).email,"onUpdate:error":e[3]||(e[3]=a=>t(s).email=a)},null,8,["disabled","modelValue","label","error"])]),n.value?(f(),x("div",P,[l(u,{type:"password",modelValue:r.existingEmailOtp,"onUpdate:modelValue":e[4]||(e[4]=a=>r.existingEmailOtp=a),name:"existingEmailOtp",label:i.$trans("user.profile.verification_otp",{attribute:t(V)}),error:t(s).existingEmailOtp,"onUpdate:error":e[5]||(e[5]=a=>t(s).existingEmailOtp=a)},null,8,["modelValue","label","error"])])):w("",!0),m.value?(f(),x("div",R,[l(u,{type:"password",modelValue:r.newEmailOtp,"onUpdate:modelValue":e[6]||(e[6]=a=>r.newEmailOtp=a),name:"newEmailOtp",label:i.$trans("user.profile.verification_otp",{attribute:r.email}),error:t(s).newEmailOtp,"onUpdate:error":e[7]||(e[7]=a=>t(s).newEmailOtp=a)},null,8,["modelValue","label","error"])])):w("",!0)])]),_:1},8,["action","form"])]),_:1})}}});export{J as default};